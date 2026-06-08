<?php

namespace App\Http\Controllers;

use App\Models\StoreTarget;
use App\Models\StoreTargetUpload;
use App\Models\StorePerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Validation\ValidationException;

class StoreTargetUploadController extends Controller
{
    /**
     * Display the upload form
     */
    public function index()
    {
        $recentUploads = StoreTargetUpload::with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $uploadTypes = [
            'store_targets' => 'Store Targets',
            'store_performance' => 'Store Performance',
            'store_master' => 'Store Master Data',
        ];

        return view('admin.utilities.master-upload', compact('recentUploads', 'uploadTypes'));
    }

    public function utilities()
    {
        return view('welcome');
    }

    /**
     * Preview uploaded file data
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'upload_type' => 'required|in:store_targets,store_performance,store_master',
        ]);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The file is empty',
                ], 422);
            }

            // Get headers
            $headers = array_shift($rows);
            $headers = array_map('trim', $headers);

            // Get sample data (first 5 rows)
            $sampleRows = array_slice($rows, 0, 5);

            // Detect column types
            $columnTypes = [];
            foreach ($headers as $index => $header) {
                $sampleValues = array_column($sampleRows, $index);
                $columnTypes[$index] = $this->detectColumnType($sampleValues);
            }

            return response()->json([
                'success' => true,
                'preview' => [
                    'headers' => $headers,
                    'sampleRows' => $sampleRows,
                    'totalRows' => count($rows),
                    'columnTypes' => $columnTypes,
                ],
                'suggested_mappings' => $this->getSuggestedMappings($headers),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reading file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate the uploaded file before processing
     */
    public function validate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'upload_type' => 'required|in:store_targets,store_performance,store_master',
            'column_mappings' => 'nullable|json',
            'auto_verify' => 'boolean',
        ]);

        try {
            $file = $request->file('file');
            $uploadType = $request->upload_type;
            $columnMappings = $request->column_mappings ? json_decode($request->column_mappings, true) : [];
            $autoVerify = $request->boolean('auto_verify', false);

            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The file is empty',
                ], 422);
            }

            // Get headers
            $headers = array_shift($rows);
            $headers = array_map('trim', $headers);

            $validationResults = [
                'total_records' => count($rows),
                'valid_records' => 0,
                'warning_records' => 0,
                'error_records' => 0,
                'validation_errors' => [],
                'warnings' => [],
            ];

            // Validate each row
            foreach ($rows as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2; // +2 because header is row 1
                $record = $this->mapRowToRecord($row, $headers, $columnMappings, $uploadType);

                if ($uploadType === 'store_targets') {
                    $validation = $this->validateStoreTargetRecord($record, $rowNumber);
                } elseif ($uploadType === 'store_performance') {
                    $validation = $this->validateStorePerformanceRecord($record, $rowNumber);
                } else {
                    $validation = $this->validateStoreMasterRecord($record, $rowNumber);
                }

                if ($validation['is_valid']) {
                    $validationResults['valid_records']++;
                }

                if ($validation['has_warning']) {
                    $validationResults['warning_records']++;
                    if (!empty($validation['warnings'])) {
                        $validationResults['warnings'] = array_merge($validationResults['warnings'], $validation['warnings']);
                    }
                }

                if (!$validation['is_valid']) {
                    $validationResults['error_records']++;
                    if (!empty($validation['errors'])) {
                        $validationResults['validation_errors'] = array_merge($validationResults['validation_errors'], $validation['errors']);
                    }
                }
            }

            // Store validation results temporarily in session
            session(['upload_validation_results' => $validationResults]);
            session(['upload_file_info' => [
                'path' => $file->getPathname(),
                'original_name' => $file->getClientOriginalName(),
                'upload_type' => $uploadType,
                'column_mappings' => $columnMappings,
                'auto_verify' => $autoVerify,
            ]]);

            return response()->json([
                'success' => true,
                'total_records' => $validationResults['total_records'],
                'valid_records' => $validationResults['valid_records'],
                'warning_records' => $validationResults['warning_records'],
                'error_records' => $validationResults['error_records'],
                'validation_errors' => $validationResults['validation_errors'],
                'warnings' => $validationResults['warnings'],
                'can_process' => $validationResults['valid_records'] > 0,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process the uploaded file and store data
     */
    public function process(Request $request)
    {
        $request->validate([
            'upload_type' => 'required|in:store_targets,store_performance,store_master',
        ]);

        $fileInfo = session('upload_file_info');
        if (!$fileInfo) {
            return response()->json([
                'success' => false,
                'message' => 'No validated file found. Please upload and validate first.',
            ], 422);
        }

        try {
            // Create upload record
            $upload = StoreTargetUpload::create([
                'original_filename' => $fileInfo['original_name'],
                'filename' => $this->generateUniqueFilename($fileInfo['original_name']),
                'upload_type' => $fileInfo['upload_type'],
                'status' => StoreTargetUpload::STATUS_PENDING,
                'uploaded_by' => auth()->id(),
                'batch_reference' => StoreTargetUpload::generateBatchReference(),
                'metadata' => [
                    'column_mappings' => $fileInfo['column_mappings'],
                    'auto_verify' => $fileInfo['auto_verify'],
                ],
            ]);

            // Load and process the file
            $spreadsheet = IOFactory::load($fileInfo['path']);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            $headers = array_shift($rows);
            $headers = array_map('trim', $headers);

            // Start processing
            $upload->startProcessing();

            $results = [
                'success' => 0,
                'failed' => 0,
                'skipped' => 0,
                'errors' => [],
            ];

            DB::beginTransaction();

            try {
                foreach ($rows as $rowIndex => $row) {
                    $rowNumber = $rowIndex + 2;
                    $record = $this->mapRowToRecord($row, $headers, $fileInfo['column_mappings'], $fileInfo['upload_type']);

                    if ($fileInfo['upload_type'] === 'store_targets') {
                        $result = $this->processStoreTargetRecord($record, $upload, $rowNumber);
                    } elseif ($fileInfo['upload_type'] === 'store_performance') {
                        $result = $this->processStorePerformanceRecord($record, $upload, $rowNumber);
                    } else {
                        $result = $this->processStoreMasterRecord($record, $upload, $rowNumber);
                    }

                    if ($result['success']) {
                        $results['success']++;
                        $upload->increment('success_records');
                    } elseif ($result['skipped']) {
                        $results['skipped']++;
                        $upload->increment('skipped_records');
                    } else {
                        $results['failed']++;
                        $results['errors'][] = $result['error'];
                        $upload->increment('failed_records');
                    }

                    $upload->increment('processed_records');

                    // Update progress every 10 records
                    if ($upload->processed_records % 10 === 0) {
                        $upload->update([
                            'metadata' => array_merge($upload->metadata ?? [], [
                                'progress' => [
                                    'processed' => $upload->processed_records,
                                    'total' => $upload->total_records,
                                    'percentage' => ($upload->processed_records / $upload->total_records) * 100,
                                ],
                            ]),
                        ]);
                    }
                }

                DB::commit();

                // Complete processing
                $upload->update([
                    'total_records' => count($rows),
                    'processing_completed_at' => now(),
                ]);
                $upload->completeProcessing();

                // Generate summaries after successful import
                if ($fileInfo['upload_type'] === 'store_targets') {
                    $this->generatePerformanceSummaries($upload->id);
                }

                // Clear session data
                session()->forget(['upload_file_info', 'upload_validation_results']);

                return response()->json([
                    'success' => true,
                    'upload_id' => $upload->id,
                    'processed_records' => $upload->processed_records,
                    'success_records' => $upload->success_records,
                    'failed_records' => $upload->failed_records,
                    'skipped_records' => $upload->skipped_records,
                    'errors' => $results['errors'],
                    'message' => 'Upload completed successfully!',
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                $upload->failProcessing($e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Processing failed: ' . $e->getMessage(),
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing upload: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get upload history
     */
    public function history()
    {
        $uploads = StoreTargetUpload::with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'uploads' => $uploads->map(function ($upload) {
                return [
                    'id' => $upload->id,
                    'original_filename' => $upload->original_filename,
                    'upload_type' => $upload->upload_type,
                    'status' => $upload->status,
                    'status_label' => $upload->status_label,
                    'total_records' => $upload->total_records,
                    'success_records' => $upload->success_records,
                    'failed_records' => $upload->failed_records,
                    'created_at' => $upload->created_at,
                    'uploaded_by' => $upload->uploadedBy?->name,
                ];
            }),
            'pagination' => [
                'current_page' => $uploads->currentPage(),
                'last_page' => $uploads->lastPage(),
                'per_page' => $uploads->perPage(),
                'total' => $uploads->total(),
            ],
        ]);
    }

    /**
     * Get upload details
     */
    public function show($id)
    {
        $upload = StoreTargetUpload::with('uploadedBy', 'processedBy')->findOrFail($id);

        return response()->json([
            'success' => true,
            'upload' => $upload->summary,
            'errors' => $upload->error_log,
            'warnings' => $upload->warning_log,
            'validation_errors' => $upload->validation_errors,
        ]);
    }

    /**
     * Download template file
     */
    public function downloadTemplate(Request $request)
    {
        $type = $request->get('type', 'store_targets');

        $headers = $this->getTemplateHeaders($type);

        // Create CSV content
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            // Add example row
            $exampleRow = $this->getExampleRow($type);
            if ($exampleRow) {
                fputcsv($file, $exampleRow);
            }

            fclose($file);
        };

        $filename = $type . '_template_' . date('Y-m-d') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // ========== PRIVATE HELPER METHODS ==========

    /**
     * Detect column type based on sample values
     */
    private function detectColumnType(array $sampleValues): string
    {
        $numericCount = 0;
        $dateCount = 0;

        foreach ($sampleValues as $value) {
            if (is_numeric($value)) {
                $numericCount++;
            }
            if (strtotime($value)) {
                $dateCount++;
            }
        }

        if ($dateCount > count($sampleValues) / 2) {
            return 'date';
        }
        if ($numericCount > count($sampleValues) / 2) {
            return 'numeric';
        }

        return 'text';
    }

    /**
     * Get suggested column mappings
     */
    private function getSuggestedMappings(array $headers): array
    {
        $suggestions = [];
        $mappingOptions = [
            'store_code' => ['store code', 'store_code', 'code', 'store id'],
            'store_name' => ['store name', 'store_name', 'name', 'store'],
            'region' => ['region', 'area', 'district'],
            'kpi' => ['kpi', 'metric', 'indicator', 'category'],
            'business_unit' => ['business unit', 'unit', 'business_unit', 'bu'],
            'target_jan' => ['jan', 'january', 'target_jan'],
            'target_feb' => ['feb', 'february', 'target_feb'],
            'actual_amount' => ['actual', 'sales', 'amount', 'actual_amount'],
        ];

        foreach ($headers as $header) {
            $lowerHeader = strtolower($header);
            foreach ($mappingOptions as $field => $keywords) {
                foreach ($keywords as $keyword) {
                    if (strpos($lowerHeader, $keyword) !== false) {
                        $suggestions[$header] = $field;
                        break 2;
                    }
                }
            }
        }

        return $suggestions;
    }

    /**
     * Map row data to record array
     */
    private function mapRowToRecord(array $row, array $headers, array $mappings, string $uploadType): array
    {
        $record = [];

        foreach ($mappings as $columnIndex => $fieldName) {
            if (isset($row[$columnIndex]) && $fieldName) {
                $value = $row[$columnIndex];

                // Handle date fields
                if (strpos($fieldName, 'date') !== false && is_numeric($value)) {
                    $value = Date::excelToDateTimeObject($value)->format('Y-m-d');
                }

                $record[$fieldName] = $value;
            }
        }

        return $record;
    }

    /**
     * Validate store target record
     */
    private function validateStoreTargetRecord(array $record, int $rowNumber): array
    {
        $result = [
            'is_valid' => true,
            'has_warning' => false,
            'errors' => [],
            'warnings' => [],
        ];

        // Required fields
        if (empty($record['store_code'])) {
            $result['is_valid'] = false;
            $result['errors'][] = [
                'row' => $rowNumber,
                'field' => 'store_code',
                'message' => 'Store code is required',
            ];
        }

        if (empty($record['kpi'])) {
            $result['is_valid'] = false;
            $result['errors'][] = [
                'row' => $rowNumber,
                'field' => 'kpi',
                'message' => 'KPI is required',
            ];
        }

        // Validate numeric values
        $monthFields = ['target_jan', 'target_feb', 'target_mar', 'target_apr', 'target_may', 'target_jun',
                        'target_jul', 'target_aug', 'target_sep', 'target_oct', 'target_nov', 'target_dec'];

        foreach ($monthFields as $field) {
            if (isset($record[$field]) && !is_numeric($record[$field])) {
                $result['is_valid'] = false;
                $result['errors'][] = [
                    'row' => $rowNumber,
                    'field' => $field,
                    'message' => "{$field} must be numeric",
                ];
            }
        }

        return $result;
    }

    /**
     * Validate store performance record
     */
    private function validateStorePerformanceRecord(array $record, int $rowNumber): array
    {
        $result = [
            'is_valid' => true,
            'has_warning' => false,
            'errors' => [],
            'warnings' => [],
        ];

        if (empty($record['store_code'])) {
            $result['is_valid'] = false;
            $result['errors'][] = [
                'row' => $rowNumber,
                'field' => 'store_code',
                'message' => 'Store code is required',
            ];
        }

        if (empty($record['kpi'])) {
            $result['is_valid'] = false;
            $result['errors'][] = [
                'row' => $rowNumber,
                'field' => 'kpi',
                'message' => 'KPI is required',
            ];
        }

        if (isset($record['actual_amount']) && !is_numeric($record['actual_amount'])) {
            $result['is_valid'] = false;
            $result['errors'][] = [
                'row' => $rowNumber,
                'field' => 'actual_amount',
                'message' => 'Actual amount must be numeric',
            ];
        }

        return $result;
    }

    /**
     * Validate store master record
     */
    private function validateStoreMasterRecord(array $record, int $rowNumber): array
    {
        return $this->validateStoreTargetRecord($record, $rowNumber);
    }

    /**
     * Process store target record
     */
    private function processStoreTargetRecord(array $record, StoreTargetUpload $upload, int $rowNumber): array
    {
        try {
            // Check if record already exists
            $existing = StoreTarget::where('store_code', $record['store_code'])
                ->where('kpi', $record['kpi'])
                ->where('target_year', $record['target_year'] ?? 2026)
                ->first();

            $data = array_merge($record, [
                'upload_batch_id' => $upload->id,
                'source_file' => $upload->original_filename,
            ]);

            if ($existing) {
                $existing->update($data);
            } else {
                StoreTarget::create($data);
            }

            return ['success' => true, 'skipped' => false];

        } catch (\Exception $e) {
            $upload->addValidationError($rowNumber, 'general', $e->getMessage());
            return ['success' => false, 'skipped' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Process store performance record
     */
    private function processStorePerformanceRecord(array $record, StoreTargetUpload $upload, int $rowNumber): array
    {
        try {
            // Find associated store target
            $storeTarget = StoreTarget::where('store_code', $record['store_code'])
                ->where('kpi', $record['kpi'])
                ->first();

            if (!$storeTarget) {
                $upload->addValidationError($rowNumber, 'store_code', 'Store target not found for this KPI');
                return ['success' => false, 'skipped' => false, 'error' => 'Store target not found'];
            }

            StorePerformance::updateOrCreate(
                [
                    'store_target_id' => $storeTarget->id,
                    'store_code' => $record['store_code'],
                    'kpi' => $record['kpi'],
                    'year' => $record['year'] ?? 2026,
                    'month' => $record['month'],
                ],
                [
                    'actual_amount' => $record['actual_amount'] ?? 0,
                    'target_amount' => $storeTarget->getTargetForMonth($record['month']),
                    'upload_batch_id' => $upload->id,
                    'notes' => $record['notes'] ?? null,
                ]
            );

            return ['success' => true, 'skipped' => false];

        } catch (\Exception $e) {
            $upload->addValidationError($rowNumber, 'general', $e->getMessage());
            return ['success' => false, 'skipped' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Process store master record
     */
    private function processStoreMasterRecord(array $record, StoreTargetUpload $upload, int $rowNumber): array
    {
        return $this->processStoreTargetRecord($record, $upload, $rowNumber);
    }

    /**
     * Generate performance summaries after upload
     */
    private function generatePerformanceSummaries(int $uploadId): void
    {
        // Get all targets from this upload
        $targets = StoreTarget::where('upload_batch_id', $uploadId)->get();

        foreach ($targets as $target) {
            // Generate monthly summaries
            for ($month = 1; $month <= 12; $month++) {
                $performances = StorePerformance::where('store_target_id', $target->id)
                    ->where('month', $month)
                    ->get();

                if ($performances->isNotEmpty()) {
                    StoreTargetSummary::updateSummary(
                        [
                            'region' => $target->region,
                            'cluster' => $target->cluster,
                            'kpi' => $target->kpi,
                            'business_unit' => $target->business_unit,
                            'year' => $target->target_year,
                            'month' => $month,
                        ],
                        $performances
                    );
                }
            }

            // Generate YTD summary
            $ytdPerformances = StorePerformance::where('store_target_id', $target->id)
                ->where('year', $target->target_year)
                ->get();

            if ($ytdPerformances->isNotEmpty()) {
                StoreTargetSummary::updateSummary(
                    [
                        'region' => $target->region,
                        'cluster' => $target->cluster,
                        'kpi' => $target->kpi,
                        'business_unit' => $target->business_unit,
                        'year' => $target->target_year,
                        'month' => null,
                    ],
                    $ytdPerformances
                );
            }
        }
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return 'uploads/targets_' . date('Ymd_His') . '_' . Str::random(8) . '.' . $extension;
    }

    /**
     * Get template headers for download
     */
    private function getTemplateHeaders(string $type): array
    {
        if ($type === 'store_targets') {
            return [
                'store_code', 'store_name', 'region', 'cluster', 'kpi', 'business_unit',
                'target_jan', 'target_feb', 'target_mar', 'target_apr', 'target_may', 'target_jun',
                'target_jul', 'target_aug', 'target_sep', 'target_oct', 'target_nov', 'target_dec',
                'target_year'
            ];
        } elseif ($type === 'store_performance') {
            return [
                'store_code', 'kpi', 'year', 'month', 'actual_amount', 'notes'
            ];
        }

        return ['store_code', 'store_name', 'region', 'kpi', 'target_amount'];
    }

    /**
     * Get example row for template
     */
    private function getExampleRow(string $type): ?array
    {
        if ($type === 'store_targets') {
            return [
                'SABC2418', 'MTN Store - Beacon Bay', 'Eastern Cape', 'East London',
                'Accessories', 'Accessories', '34555.30', '58980.80', '44650.60',
                '43922.50', '53424.20', '62770.90', '47699.10', '46310.50',
                '45340.30', '51550.90', '44627.50', '55783.70', '2026'
            ];
        } elseif ($type === 'store_performance') {
            return ['SABC2418', 'Accessories', '2026', '5', '58000.00', 'Actual sales for May'];
        }

        return null;
    }
}

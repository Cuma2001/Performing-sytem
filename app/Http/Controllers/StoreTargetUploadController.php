<?php
// app/Http/Controllers/StoreTargetUploadController.php

namespace App\Http\Controllers;

use App\Models\StoreTargetUpload;
use App\Models\StoreTarget;
use App\Models\SupervisorTarget;
use App\Models\CompanyTarget;
use App\Models\MtnTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreTargetUploadController extends Controller
{
    public function utilities()
    {
        return view('utilities.index');
    }

    public function index()
    {
        return view('utilities.master-upload');
    }

    /**
     * Handle the file upload and processing
     */
    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,csv,xls,txt',
                'mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-office',
                'max:10240',
            ],
            'upload_type' => 'required|in:store,supervisor,company,mtn,sales_agent'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $uploadType = $request->upload_type;
            $originalName = $file->getClientOriginalName();
            $fileName = date('Y-m-d_H-i-s') . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Store file
            $path = $file->storeAs('kpi_uploads', $fileName, 'public');
            $fileHash = md5_file($file->getPathname());

            // Allow re-uploading the same spreadsheet when users intentionally reprocess it.
            // The import itself is idempotent via updateOrInsert() and the upload history
            // should still be retained for each run instead of blocking legitimate retries.
            
            // Create upload record
            $upload = StoreTargetUpload::create([
                'filename' => $fileName,
                'original_filename' => $originalName,
                'file_path' => $path,
                'file_hash' => $fileHash,
                'type' => $uploadType,
                'total_records' => 0,
                'processed_records' => 0,
                'success_records' => 0,
                'failed_records' => 0,
                'status' => StoreTargetUpload::STATUS_PENDING,
                'uploaded_by' => auth()->id(),
            ]);
            
            // Process the file
            $result = $this->processFile($upload, $file, $uploadType);
            
            // Update upload record
            $upload->update([
                'total_records' => $result['total'] ?? 0,
                'success_records' => $result['successful'] ?? 0,
                'failed_records' => $result['failed'] ?? 0,
                'status' => StoreTargetUpload::STATUS_COMPLETED,
                'processing_completed_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'records' => $upload->total_records,
                'success_records' => $upload->success_records,
                'failed_records' => $upload->failed_records,
                'upload_id' => $upload->id
            ]);
            
        } catch (\Exception $e) {
            \Log::error('File upload failed: ' . $e->getMessage(), [
                'file' => $request->file('file')?->getClientOriginalName(),
                'type' => $request->upload_type
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processFile(StoreTargetUpload $upload, $file, $type)
    {
        try {
            $upload->startProcessing();
            
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            $header = array_map(function ($value) {
                return Str::snake(trim((string) $value));
            }, array_shift($rows));
            if (in_array($type, ['store', 'mtn'], true)) {
                $header = $this->normalizeStoreHeaders($header);
            }
            $successful = 0;
            $failed = 0;
            
            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) {
                    continue;
                }
                
                try {
                    $data = array_combine($header, array_pad($row, count($header), null));
                    if ($type === 'store' && empty($data['store_code'])) {
                        $data['store_code'] = $this->fallbackStoreCode($data);
                    }
                    
                    // Validate row data
                    if (!$this->validateRowData($data, $type)) {
                        $failed++;
                        $upload->addValidationError($index + 2, 'row', 'Invalid data format');
                        continue;
                    }
                    
                    // Prepare data for insertion
                    $insertData = $this->prepareRowData($data, $type, $upload->id);
                    
                    // Insert into appropriate table
                    $this->insertRecord($type, $insertData);
                    $successful++;
                    $upload->increment('processed_records');
                    
                } catch (\Exception $e) {
                    $failed++;
                    $upload->increment('processed_records');
                    $upload->addError("Row " . ($index + 2) . " failed: " . $e->getMessage());
                }
            }
            
            // Update counts
            $upload->update([
                'total_records' => $successful + $failed,
                'success_records' => $successful,
                'failed_records' => $failed
            ]);
            
            return [
                'total' => $successful + $failed,
                'successful' => $successful,
                'failed' => $failed
            ];
            
        } catch (\Exception $e) {
            $upload->failProcessing($e->getMessage());
            throw $e;
        }
    }

    private function insertRecord($type, $data)
    {
        switch ($type) {
            case 'store':
                $this->upsertStoreTarget($data);
                break;
            case 'supervisor':
                SupervisorTarget::updateOrCreate(
                    ['supervisor_code' => $data['supervisor_code'], 'store_code' => $data['store_code'], 'month' => $data['month']],
                    $data
                );
                break;
            case 'company':
                CompanyTarget::updateOrCreate(
                    ['month' => $data['month']],
                    $data
                );
                break;
            case 'mtn':
                MtnTarget::updateOrCreate(
                    ['mtn_code' => $data['mtn_code'], 'store_code' => $data['store_code'], 'month' => $data['month']],
                    $data
                );
                break;
            case 'sales_agent':
                $employee = \App\Models\Employee::where('employee_code', $data['employee_code'])->firstOrFail();
                $store = \App\Models\Store::where('code', $data['store_code'])->firstOrFail();
                DB::table('targets')->updateOrInsert(
                    [
                        'employee_id' => $employee->id,
                        'year' => (int) $data['year'],
                        'month' => (int) $data['month'],
                        'target_type' => $data['target_type'] ?? 'monthly',
                    ],
                    [
                        'region_id' => $store->region_id,
                        'store_id' => $store->id,
                        'sales_target' => $data['target'],
                        'quantity_target' => $data['quantity_target'] ?? null,
                        'revenue_target' => $data['revenue_target'] ?? null,
                        'customer_target' => $data['customer_target'] ?? null,
                        'quarter' => $data['quarter'] ?? null,
                        'upload_batch_id' => $data['upload_batch_id'] ?? null,
                        'status' => 'pending',
                        'achievement_percentage' => 0,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
                break;
            default:
                throw new \Exception('Unknown upload type: ' . $type);
        }
    }

    private function prepareRowData(array $data, string $type, int $uploadId)
    {
        $baseData = [
            'upload_batch_id' => $uploadId,
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        switch ($type) {
            case 'store':
                return array_merge([
                    'store_code' => $data['store_code'] ?? null,
                    'store_name' => $data['store_name'] ?? null,
                    'ownership' => $data['ownership'] ?? 'NON OWNED',
                    'dealer' => $data['dealer'] ?? null,
                    'store_type' => $data['store_type'] ?? null,
                    'region' => $data['region'] ?? null,
                    'cluster' => $data['cluster'] ?? null,
                    'kpi' => $data['kpi'] ?? null,
                    'business_unit' => $data['business_unit'] ?? null,
                    'annual_budget' => $data['annual_budget'] ?? 0,
                    'target_jan' => $data['target_jan'] ?? null,
                    'target_feb' => $data['target_feb'] ?? null,
                    'target_mar' => $data['target_mar'] ?? null,
                    'target_apr' => $data['target_apr'] ?? null,
                    'target_may' => $data['target_may'] ?? null,
                    'target_jun' => $data['target_jun'] ?? null,
                    'target_jul' => $data['target_jul'] ?? null,
                    'target_aug' => $data['target_aug'] ?? null,
                    'target_sep' => $data['target_sep'] ?? null,
                    'target_oct' => $data['target_oct'] ?? null,
                    'target_nov' => $data['target_nov'] ?? null,
                    'target_dec' => $data['target_dec'] ?? null,
                    'target_year' => $this->targetYear($data),
                    'source_file' => $data['source_file'] ?? null,
                ], $baseData);
                
            case 'supervisor':
                return array_merge([
                    'supervisor_code' => $data['supervisor_code'] ?? null,
                    'store_code' => $data['store_code'] ?? null,
                    'kpi' => $data['kpi'] ?? null,
                    'target' => $data['target'] ?? 0,
                    'month' => $data['month'] ?? now()->format('Y-m'),
                ], $baseData);
                
            case 'company':
                return array_merge([
                    'kpi' => $data['kpi'] ?? null,
                    'target' => $data['target'] ?? 0,
                    'month' => $data['month'] ?? now()->format('Y-m'),
                ], $baseData);
                
            case 'mtn':
                return array_merge([
                    'mtn_code' => $data['mtn_code'] ?? null,
                    'store_code' => $data['store_code'] ?? null,
                    'ownership' => $data['ownership'] ?? null,
                    'dealer' => $data['dealer'] ?? null,
                    'store_type' => $data['store_type'] ?? null,
                    'region' => $data['region'] ?? null,
                    'cluster' => $data['cluster'] ?? null,
                    'kpi' => $data['kpi'] ?? null,
                    'business_unit' => $data['business_unit'] ?? null,
                    'annual_budget' => $data['annual_budget'] ?? 0,
                    'target' => $data['target'] ?? 0,
                    'target_jan' => $data['target_jan'] ?? 0,
                    'target_feb' => $data['target_feb'] ?? 0,
                    'target_mar' => $data['target_mar'] ?? 0,
                    'target_apr' => $data['target_apr'] ?? 0,
                    'target_may' => $data['target_may'] ?? 0,
                    'target_jun' => $data['target_jun'] ?? 0,
                    'target_jul' => $data['target_jul'] ?? 0,
                    'target_aug' => $data['target_aug'] ?? 0,
                    'target_sep' => $data['target_sep'] ?? 0,
                    'target_oct' => $data['target_oct'] ?? 0,
                    'target_nov' => $data['target_nov'] ?? 0,
                    'target_dec' => $data['target_dec'] ?? 0,
                    'target_year' => $this->targetYear($data),
                    'total_target' => $data['total_target'] ?? 0,
                    'month' => $data['month'] ?? now()->format('Y-m'),
                ], $baseData);

            case 'sales_agent':
                return array_merge([
                    'employee_code' => $data['employee_code'] ?? null,
                    'store_code' => $data['store_code'] ?? null,
                    'target' => $data['target'] ?? 0,
                    'year' => $data['year'] ?? now()->year,
                    'month' => $data['month'] ?? now()->month,
                    'quantity_target' => $data['quantity_target'] ?? null,
                    'revenue_target' => $data['revenue_target'] ?? null,
                    'customer_target' => $data['customer_target'] ?? null,
                    'target_type' => $data['target_type'] ?? 'monthly',
                    'quarter' => $data['quarter'] ?? null,
                ], ['upload_batch_id' => $uploadId]);
                
            default:
                throw new \Exception('Unknown upload type: ' . $type);
        }
    }

    private function validateRowData($data, $type)
    {
        $requiredFields = $this->getRequiredFields($type);
        
        foreach ($requiredFields as $field) {
            if (in_array($type, ['store', 'mtn'], true) && $field === 'target' && $this->hasMonthlyTarget($data)) {
                continue;
            }
            if (empty($data[$field] ?? null)) {
                return false;
            }
        }
        
        // Validate target is numeric and positive
        if (isset($data['target']) && $data['target'] !== '' && (!is_numeric($data['target']) || $data['target'] < 0)) {
            return false;
        }

        if ($type === 'sales_agent' && ((int) ($data['year'] ?? 0) < 2000 || (int) ($data['month'] ?? 0) < 1 || (int) ($data['month'] ?? 0) > 12)) {
            return false;
        }
        
        return true;
    }

    private function getRequiredFields($type)
    {
        $fields = [
            'store' => ['store_code', 'target'],
            'supervisor' => ['supervisor_code', 'store_code', 'target'],
            'company' => ['target'],
            'mtn' => ['mtn_code', 'store_code', 'target'],
            'sales_agent' => ['employee_code', 'store_code', 'target', 'year', 'month']
        ];
        
        return $fields[$type] ?? [];
    }

    private function normalizeStoreHeaders(array $headers): array
    {
        $aliases = [
            'owner' => 'ownership',
            'ownership' => 'ownership',
            'business' => 'business_unit',
            'business_unit' => 'business_unit',
            'budget' => 'annual_budget',
            'total' => 'total_target',
        ];

        return array_map(function (string $header) use ($aliases): string {
            if (isset($aliases[$header])) {
                return $aliases[$header];
            }

            if (preg_match('/^(?:20\\d{2}_)?(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)(?:_target)?$/', $header, $matches)) {
                return 'target_' . $matches[1];
            }

            if (preg_match('/^20\\d{2}_budget$/', $header)) {
                return 'annual_budget';
            }

            return $header;
        }, $headers);
    }

    private function fallbackStoreCode(array $data): string
    {
        $identity = implode('|', [
            $data['dealer'] ?? '',
            $data['store_type'] ?? '',
            $data['region'] ?? '',
            $data['cluster'] ?? '',
            $data['kpi'] ?? '',
            $data['business_unit'] ?? '',
        ]);

        return 'IMPORT-' . strtoupper(substr(sha1($identity), 0, 12));
    }

    private function targetYear(array $data): int
    {
        return (int) ($data['target_year'] ?? ($data['year'] ?? now()->year));
    }

    private function hasMonthlyTarget(array $data): bool
    {
        foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $month) {
            if (isset($data['target_' . $month]) && is_numeric($data['target_' . $month])) {
                return true;
            }
        }

        return false;
    }

    private function upsertStoreTarget(array $data): void
    {
        $values = $this->prepareRowData($data, 'store', (int) ($data['upload_batch_id'] ?? 0));
        $month = strtolower((string) ($data['month'] ?? ''));
        if ($month !== '' && is_numeric($data['target'] ?? null)) {
            $monthNumber = is_numeric($month) ? (int) $month : date_parse($month)['month'];
            if ($monthNumber >= 1 && $monthNumber <= 12) {
                $values['target_' . strtolower(date('M', mktime(0, 0, 0, $monthNumber, 1)))] = $data['target'];
            }
        }

        foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $monthName) {
            if (isset($data['target_' . $monthName]) && is_numeric($data['target_' . $monthName])) {
                $values['target_' . $monthName] = $data['target_' . $monthName];
            }
        }

        DB::table('store_targets')->updateOrInsert(
            ['store_code' => $values['store_code'], 'kpi' => $values['kpi'], 'target_year' => $values['target_year']],
            $values
        );
    }

    /**
     * Get upload history for AJAX
     */
    public function getHistory()
    {
        $history = StoreTargetUpload::with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($upload) {
                return [
                    'id' => $upload->id,
                    'created_at' => $upload->created_at->format('Y-m-d H:i:s'),
                    'file_name' => $upload->original_filename,
                    'type' => $upload->type,
                    'status' => $upload->status_label,
                    'status_badge' => $upload->status_badge_class,
                    'records' => $upload->total_records,
                    'success_records' => $upload->success_records,
                    'failed_records' => $upload->failed_records,
                ];
            });
        
        return response()->json($history);
    }

    public function history()
    {
        $history = StoreTargetUpload::with('uploadedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        if (request()->ajax()) {
            return response()->json($history);
        }
        
        return view('utilities.history', compact('history'));
    }

    public function show($id)
    {
        $upload = StoreTargetUpload::with('uploadedBy')
            ->findOrFail($id);
        
        return view('utilities.show', compact('upload'));
    }

    public function downloadTemplate(Request $request)
    {
        $type = $request->get('type', 'store');
        
        $headers = [
            'store' => ['store_code', 'store_name', 'kpi', 'business_unit', 'target_year', 'target_jan', 'target_feb', 'target_mar', 'target_apr', 'target_may', 'target_jun', 'target_jul', 'target_aug', 'target_sep', 'target_oct', 'target_nov', 'target_dec'],
            'supervisor' => ['supervisor_code', 'store_code', 'kpi', 'target', 'month'],
            'company' => ['kpi', 'target', 'month'],
            'mtn' => ['mtn_code', 'store_code', 'kpi', 'target', 'month'],
            'sales_agent' => ['employee_code', 'store_code', 'target', 'year', 'month', 'quantity_target', 'revenue_target', 'customer_target', 'target_type']
        ];
        
        $fileName = $type . '_template.xlsx';
        $path = storage_path('app/templates/' . $fileName);
        
        if (!file_exists($path)) {
            $this->generateTemplate($path, $headers[$type] ?? []);
        }
        
        return response()->download($path, $type . '_template.xlsx');
    }

    private function generateTemplate($path, $headers)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers with styling
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }
        
        // Add example data row
        $row = 2;
        $col = 'A';
        foreach ($headers as $header) {
            $exampleValue = match($header) {
                'store_code' => 'STORE001',
                'supervisor_code' => 'SUP001',
                'mtn_code' => 'MTN001',
                'target' => '1000',
                'month' => date('Y-m'),
                default => 'example'
            };
            $sheet->setCellValue($col . $row, $exampleValue);
            $col++;
        }
        
        // Create directory if it doesn't exist
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($path);
    }

    public function retry($id)
    {
        $upload = StoreTargetUpload::findOrFail($id);
        
        if (!$upload->isFailed()) {
            return response()->json([
                'success' => false,
                'message' => 'Only failed uploads can be retried'
            ], 422);
        }
        
        try {
            $filePath = storage_path('app/public/' . $upload->file_path);
            
            if (!file_exists($filePath)) {
                throw new \Exception('File not found');
            }
            
            $file = new \Illuminate\Http\UploadedFile($filePath, $upload->original_filename);
            
            // Reset the upload record
            $upload->update([
                'status' => StoreTargetUpload::STATUS_PENDING,
                'processed_records' => 0,
                'success_records' => 0,
                'failed_records' => 0,
                'error_log' => [],
                'validation_errors' => [],
                'processing_started_at' => null,
                'processing_completed_at' => null,
            ]);
            
            // Process again
            $result = $this->processFile($upload, $file, $upload->type);
            
            return response()->json([
                'success' => true,
                'message' => 'File reprocessed successfully',
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            $upload->failProcessing($e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reprocess: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $upload = StoreTargetUpload::findOrFail($id);
            
            // Delete associated records based on type
            $this->deleteAssociatedRecords($upload);
            
            // Delete physical file
            if ($upload->file_path && Storage::disk('public')->exists($upload->file_path)) {
                Storage::disk('public')->delete($upload->file_path);
            }
            
            $upload->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Upload record deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }

    private function deleteAssociatedRecords($upload)
    {
        switch ($upload->type) {
            case 'store':
                StoreTarget::where('upload_batch_id', $upload->id)->delete();
                break;
            case 'supervisor':
                SupervisorTarget::where('upload_batch_id', $upload->id)->delete();
                break;
            case 'company':
                CompanyTarget::where('upload_batch_id', $upload->id)->delete();
                break;
            case 'mtn':
                MtnTarget::where('upload_batch_id', $upload->id)->delete();
                break;
            case 'sales_agent':
                DB::table('targets')->where('upload_batch_id', $upload->id)->delete();
                break;
        }
    }

    public function stats()
    {
        $stats = [
            'total_uploads' => StoreTargetUpload::count(),
            'pending' => StoreTargetUpload::pending()->count(),
            'processing' => StoreTargetUpload::where('status', StoreTargetUpload::STATUS_PROCESSING)->count(),
            'completed' => StoreTargetUpload::completed()->count(),
            'failed' => StoreTargetUpload::failed()->count(),
            'total_records' => StoreTargetUpload::sum('total_records'),
            'success_records' => StoreTargetUpload::sum('success_records'),
            'failed_records' => StoreTargetUpload::sum('failed_records'),
            'recent_uploads' => StoreTargetUpload::with('uploadedBy')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
        
        return response()->json($stats);
    }
}
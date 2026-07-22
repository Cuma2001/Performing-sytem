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
            'file' => 'required|file|mimes:xlsx,csv,xls|max:10240',
            'upload_type' => 'required|in:store,supervisor,company,mtn'
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
            
            // Check for duplicate
            $existing = StoreTargetUpload::where('file_hash', $fileHash)
                ->where('status', '!=', StoreTargetUpload::STATUS_FAILED)
                ->first();
                
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'This file has already been uploaded'
                ], 409);
            }
            
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
            
            $header = array_shift($rows);
            $successful = 0;
            $failed = 0;
            
            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) {
                    continue;
                }
                
                try {
                    $data = array_combine($header, $row);
                    
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
                    
                } catch (\Exception $e) {
                    $failed++;
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
                StoreTarget::updateOrCreate(
                    ['store_code' => $data['store_code'], 'month' => $data['month']],
                    $data
                );
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
                    'target' => $data['target'] ?? 0,
                    'month' => $data['month'] ?? now()->format('Y-m'),
                ], $baseData);
                
            case 'supervisor':
                return array_merge([
                    'supervisor_code' => $data['supervisor_code'] ?? null,
                    'store_code' => $data['store_code'] ?? null,
                    'target' => $data['target'] ?? 0,
                    'month' => $data['month'] ?? now()->format('Y-m'),
                ], $baseData);
                
            case 'company':
                return array_merge([
                    'target' => $data['target'] ?? 0,
                    'month' => $data['month'] ?? now()->format('Y-m'),
                ], $baseData);
                
            case 'mtn':
                return array_merge([
                    'mtn_code' => $data['mtn_code'] ?? null,
                    'store_code' => $data['store_code'] ?? null,
                    'target' => $data['target'] ?? 0,
                    'month' => $data['month'] ?? now()->format('Y-m'),
                ], $baseData);
                
            default:
                throw new \Exception('Unknown upload type: ' . $type);
        }
    }

    private function validateRowData($data, $type)
    {
        $requiredFields = $this->getRequiredFields($type);
        
        foreach ($requiredFields as $field) {
            if (empty($data[$field] ?? null)) {
                return false;
            }
        }
        
        // Validate target is numeric and positive
        if (isset($data['target']) && (!is_numeric($data['target']) || $data['target'] < 0)) {
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
            'mtn' => ['mtn_code', 'store_code', 'target']
        ];
        
        return $fields[$type] ?? [];
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
            'store' => ['store_code', 'target', 'month'],
            'supervisor' => ['supervisor_code', 'store_code', 'target', 'month'],
            'company' => ['target', 'month'],
            'mtn' => ['mtn_code', 'store_code', 'target', 'month']
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
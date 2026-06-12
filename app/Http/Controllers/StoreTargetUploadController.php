<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function preview(Request $request)
    {
        // Handle file preview logic
        return response()->json(['message' => 'Preview processed']);
    }

    public function validateUpload(Request $request)
    {
        // Handle validation
        return response()->json(['message' => 'Validation complete']);
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls',
            'upload_type' => 'required|in:store,supervisor,company,mtn'
        ]);

        $file = $request->file('file');
        $uploadType = $request->upload_type;
        $fileName = $file->getClientOriginalName();
        
        // Store file
        $path = $file->storeAs('kpi_uploads', date('Y-m-d_H-i-s') . '_' . $fileName);
        
        // Process file based on type
        $records = $this->processFile($file, $uploadType);
        
        // Save to database
        DB::table('kpi_upload_history')->insert([
            'file_name' => $fileName,
            'type' => $uploadType,
            'records' => $records,
            'status' => 'completed',
            'uploaded_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully',
            'records' => $records
        ]);
    }

    private function processFile($file, $type)
    {
        // Add your Excel/CSV parsing logic here
        // This is a placeholder - implement based on your file structure
        return 10; // Return number of records processed
    }

    public function history()
    {
        $history = DB::table('kpi_upload_history')
            ->orderBy('created_at', 'desc')
            ->get();
        
        if (request()->ajax()) {
            return response()->json($history);
        }
        
        return view('utilities.history', compact('history'));
    }

    public function getHistoryData()
    {
        return response()->json(
            DB::table('kpi_upload_history')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        );
    }

    public function show($id)
    {
        $upload = DB::table('kpi_upload_history')->find($id);
        return view('utilities.show', compact('upload'));
    }

    public function downloadTemplate()
    {
        // Return template file
        $path = storage_path('app/templates/kpi_template.xlsx');
        return response()->download($path);
    }
}
<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KPIImport;

public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv,pdf'
    ]);

    Excel::import(new KPIImport, $request->file('file'));

    return back()->with('success', 'KPI uploaded successfully');
}

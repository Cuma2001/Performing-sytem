<?php

namespace App\Http\Controllers;

use App\Models\KPI;
use Illuminate\Http\Request;

class KPIController extends Controller
{
    public function index()
    {
        $kpis = KPI::latest()->get();

        return view('kpis.index', compact('kpis'));
    }

    public function create()
    {
        return view('kpis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kpi_name' => 'required',
            'kpi_category' => 'required',
            'kpi_type' => 'required',
            'weighting' => 'required',
            'target_value' => 'required',
            'financial_period' => 'required'
        ]);

        KPI::create($request->all());

        return redirect()->route('kpis.index')
            ->with('success', 'KPI created successfully');
    }

    public function distribution()
    {
        return view('kpis.distribution');
    }

    public function upload()
    {
        return view('kpis.upload');
    }
}
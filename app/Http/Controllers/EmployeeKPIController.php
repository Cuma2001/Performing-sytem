<?php

namespace App\Http\Controllers;

use App\Models\EmployeeKPI;
use App\Models\User;
use App\Models\KPI;
use Illuminate\Http\Request;

class EmployeeKPIController extends Controller
{
    public function index()
    {
        $employeeKpis = EmployeeKPI::with('user')->get();

        return view('employee-kpis.index', compact('employeeKpis'));
    }

    public function create()
    {
        $users = User::all();
        $kpis = KPI::all();

        return view('employee-kpis.create', compact('users', 'kpis'));
    }

    public function store(Request $request)
    {
        EmployeeKPI::create($request->all());

        return redirect()->route('employee-kpis.index')
            ->with('success', 'Employee KPI assigned successfully');
    }
}
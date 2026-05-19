<?php

namespace App\Http\Controllers;

use App\Models\EmployeeKPI;
use App\Models\KPI;
use App\Models\Store;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalStores = Store::count();
        $totalKpis = KPI::count();
        $employeeKpis = EmployeeKPI::count();

        return view('dashboard', compact(
            'totalUsers',
            'totalStores',
            'totalKpis',
            'employeeKpis'
        ));
    }
}
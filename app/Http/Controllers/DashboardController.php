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
        $user = auth()->user();

        $kpis = EmployeeKPI::where('user_id', $user->id)->get();

        $avgScore = $kpis->avg('score');

        $salesScore = $avgScore * 0.8;
        $generalScore = $avgScore * 0.2;

        $finalScore = $salesScore + $generalScore;

        return view('dashboard', compact('kpis','finalScore'));
    }
}
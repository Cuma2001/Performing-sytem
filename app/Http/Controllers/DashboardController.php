<?php

namespace App\Http\Controllers;

use App\Models\KPI;
use App\Models\Store;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Default score
        $finalScore = 0;

        return view('dashboard', compact('finalScore'));
    }
}
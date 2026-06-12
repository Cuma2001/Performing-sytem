<?php

namespace App\Http\Controllers;

use App\Models\KPI;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get user's role name
        $role = DB::table('roles')->find($user->role_id);
        $roleName = $role?->name ?? 'Guest';

        // Route to role-specific dashboard
        if (in_array($roleName, ['Superadmin', 'CEO/HR'])) {
            return $this->ceoHrDashboard($user);
        } elseif ($roleName === 'Supervisor') {
            return $this->supervisorDashboard($user);
        } elseif ($roleName === 'Salesperson') {
            return $this->salespersonDashboard($user);
        }

        // Fallback to generic dashboard
        return view('dashboard');
    }

    private function ceoHrDashboard($user)
    {
        // Get overview data for CEO/HR
        $totalUsers = User::count();
        $totalStores = Store::count();
        $activeSalesRecords = DB::table('sales_records')->count();
        $totalRevenue = DB::table('sales_records')->sum('amount') ?? 0;

        return view('dashboards.dashboard-ceo-hr', [
            'totalUsers' => $totalUsers,
            'totalStores' => $totalStores,
            'activeSalesRecords' => $activeSalesRecords,
            'totalRevenue' => $totalRevenue,
        ]);
    }

    private function supervisorDashboard($user)
    {
        // Get store-specific data for Supervisor
        $userStore = $user->store_id ? Store::find($user->store_id) : null;
        $storeTarget = $user->store_id ? DB::table('store_targets')->where('store_id', $user->store_id)->first() : null;
        $storePerformance = $user->store_id ? DB::table('store_performance')->where('store_id', $user->store_id)->first() : null;
        $teamMembers = User::where('store_id', $user->store_id)->count();

        return view('dashboards.dashboard-supervisor', [
            'userStore' => $userStore,
            'storeTarget' => $storeTarget,
            'storePerformance' => $storePerformance,
            'teamMembers' => $teamMembers,
        ]);
    }

    private function salespersonDashboard($user)
    {
        // Get personal sales data for Salesperson
        $personalSalesRecords = DB::table('sales_records')->where('employee_id', $user->id)->count();
        $personalRevenue = DB::table('sales_records')->where('employee_id', $user->id)->sum('amount') ?? 0;
        $userStore = $user->store_id ? Store::find($user->store_id) : null;
        $recentSales = DB::table('sales_records')
            ->where('employee_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboards.dashboard-salesperson', [
            'personalSalesRecords' => $personalSalesRecords,
            'personalRevenue' => $personalRevenue,
            'userStore' => $userStore,
            'recentSales' => $recentSales,
        ]);
    }
}

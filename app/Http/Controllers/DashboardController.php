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

    public function supervisorDashboard($user = null)
{
    if (!$user) {
        $user = auth()->user();
    }
    
    // Get store-specific data for Supervisor
    $userStore = $user->store_id ? Store::find($user->store_id) : null;
    $storeTarget = $user->store_id ? DB::table('store_targets')->where('store_id', $user->store_id)->first() : null;
    $storePerformance = $user->store_id ? DB::table('store_performance')->where('store_id', $user->store_id)->first() : null;
    $teamMembers = User::where('store_id', $user->store_id)->count();
    
    // Get team performance data with KPI scores
    $teamPerformance = DB::table('users')
        ->leftJoin('sales_records', 'users.id', '=', 'sales_records.employee_id')
        ->select(
            'users.id', 
            'users.name', 
            DB::raw('COALESCE(SUM(sales_records.amount), 0) as total_revenue'), 
            DB::raw('COUNT(sales_records.id) as sales_count'),
            DB::raw('COALESCE(SUM(sales_records.amount) / 1000, 0) as kpi_score')
        )
        ->where('users.store_id', $user->store_id)
        ->where('users.role_id', 4) // Salesperson role
        ->groupBy('users.id', 'users.name')
        ->orderBy('total_revenue', 'desc')
        ->get();
        
    // Calculate store rank among all stores
    $storeRank = DB::table('store_performance')
        ->where('month', date('Y-m'))
        ->orderBy('kpi_score', 'desc')
        ->pluck('store_id')
        ->search($user->store_id) + 1;
        
    $totalStores = DB::table('stores')->count();
    
    
    
    // Generate alerts based on performance
    $alerts = [];
    if (($storePerformance->kpi_score ?? 92.5) < 85) {
        $alerts[] = 'Store KPI is below 85% - Immediate action required';
    }
    if (($storePerformance->sales_kpi ?? 0) < 80) {
        $alerts[] = 'Sales KPI is underperforming - Team coaching recommended';
    }
    
    // Count active team members (with sales in current month)
    $activeTeamMembers = DB::table('sales_records')
        ->whereIn('employee_id', User::where('store_id', $user->store_id)->pluck('id'))
        ->whereMonth('created_at', date('m'))
        ->distinct('employee_id')
        ->count('employee_id');

    return view('dashboards.dashboard-supervisor', [
        'userStore' => $userStore,
        'storeTarget' => $storeTarget,
        'storePerformance' => $storePerformance,
        'teamMembers' => $teamMembers,
        'teamPerformance' => $teamPerformance,
        'storeRank' => $storeRank,
        'totalStores' => $totalStores,
        'alerts' => $alerts,
        'activeTeamMembers' => $activeTeamMembers,
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

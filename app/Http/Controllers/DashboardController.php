<?php

namespace App\Http\Controllers;

use App\Models\Employee;
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
        $roleName = $role?->name ?? $user->role ?? 'Guest';

        // Route to role-specific dashboard
        if (in_array(strtolower($roleName), ['superadmin', 'ceo/hr', 'ceo', 'hr', 'admin'])) {
            return $this->ceoHrDashboard($user);
        } elseif (strtolower($roleName) === 'supervisor') {
            return $this->supervisorDashboard($user);
        } elseif (strtolower($roleName) === 'salesperson') {
            return $this->salespersonDashboard($user);
        }

        // Fallback to generic dashboard
        return view('dashboard');
    }

    public function ceoHrDashboard($user = null)
    {
        // Get overview data for CEO/HR
        $totalUsers = User::count();
        $totalStores = Store::count();
        $activeSalesRecords = DB::table('sales_records')->count();
        $totalRevenue = DB::table('sales_records')->sum('amount') ?? 0;
        $stores = Store::with(['parentStore', 'manager'])->get();

        return view('dashboards.dashboard-ceo-hr', [
            'totalUsers' => $totalUsers,
            'totalStores' => $totalStores,
            'activeSalesRecords' => $activeSalesRecords,
            'totalRevenue' => $totalRevenue,
            'stores' => $stores,
        ]);
    }

    public function supervisorDashboard($user = null)
    {
        $user ??= auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $now = now();
        $userStore = $user->store_id ? Store::find($user->store_id) : null;
        $storeTarget = $userStore
            ? DB::table('store_targets')
                ->where('store_code', $userStore->code)
                ->where('target_year', $now->year)
                ->first()
            : null;

        if ($storeTarget) {
            $storeTarget->monthly_target = (float) ($storeTarget->{'target_'.strtolower($now->format('M'))} ?? 0);
        }

        $storePerformance = $userStore
            ? DB::table('store_performance')
                ->where('store_id', $userStore->id)
                ->where('year', $now->year)
                ->where('month', $now->month)
                ->latest('id')
                ->first()
            : null;

        if ($storePerformance) {
            $storePerformance->kpi_score = (float) $storePerformance->achievement_percentage;
            $storePerformance->progress = (float) $storePerformance->achievement_percentage;
            $storePerformance->sales_kpi = (float) $storePerformance->achievement_percentage;
        }

        $teamMembers = $userStore ? Employee::where('store_id', $userStore->id)->where('is_active', true)->count() : 0;
        $teamPerformance = $userStore
            ? DB::table('employees')
                ->leftJoin('sales_records', function ($join) use ($now) {
                    $join->on('employees.id', '=', 'sales_records.employee_id')
                        ->whereYear('sales_records.sale_date', $now->year)
                        ->whereMonth('sales_records.sale_date', $now->month);
                })
                ->where('employees.store_id', $userStore->id)
                ->where('employees.is_active', true)
                ->select('employees.id', 'employees.first_name as name', DB::raw('COALESCE(SUM(sales_records.amount), 0) as total_revenue'), DB::raw('COUNT(sales_records.id) as sales_count'), DB::raw('COALESCE(SUM(sales_records.amount) / 1000, 0) as kpi_score'))
                ->groupBy('employees.id', 'employees.first_name')
                ->orderByDesc('total_revenue')
                ->get()
            : collect();

        $rankedStoreIds = DB::table('store_performance')
            ->where('year', $now->year)
            ->where('month', $now->month)
            ->whereNotNull('store_id')
            ->select('store_id', DB::raw('AVG(achievement_percentage) as score'))
            ->groupBy('store_id')
            ->orderByDesc('score')
            ->pluck('store_id');
        $rankIndex = $userStore ? $rankedStoreIds->search($userStore->id, true) : false;
        $storeRank = $rankIndex === false ? null : $rankIndex + 1;
        $totalStores = Store::count();

        $alerts = [];
        if ($storePerformance && $storePerformance->kpi_score < 85) {
            $alerts[] = 'Store KPI is below 85% - Immediate action required';
        }

        $activeTeamMembers = $userStore
            ? DB::table('sales_records')->where('store_id', $userStore->id)->whereYear('sale_date', $now->year)->whereMonth('sale_date', $now->month)->distinct()->count('employee_id')
            : 0;

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

    public function salespersonDashboard($user = null)
    {
        $user ??= auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $employeeId = Employee::where('user_id', $user->id)->value('id');
        // Get personal sales data for Salesperson
        $personalSalesRecords = $employeeId ? DB::table('sales_records')->where('employee_id', $employeeId)->count() : 0;
        $personalRevenue = $employeeId ? DB::table('sales_records')->where('employee_id', $employeeId)->sum('amount') : 0;
        $userStore = $user->store_id ? Store::find($user->store_id) : null;
        $recentSales = DB::table('sales_records')
            ->where('employee_id', $employeeId ?? 0)
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

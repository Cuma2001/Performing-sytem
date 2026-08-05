<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();

        $months = collect(range(5, 0))->map(fn ($offset) => $now->copy()->subMonths($offset)->format('M Y'))->toArray();

        $salesData = collect(range(5, 0))->map(function ($offset) use ($now) {
            $rangeStart = $now->copy()->subMonths($offset)->startOfMonth()->toDateString();
            $rangeEnd = $now->copy()->subMonths($offset)->endOfMonth()->toDateString();

            return (float) DB::table('sales_records')
                ->whereBetween('sale_date', [$rangeStart, $rangeEnd])
                ->sum('amount');
        })->toArray();

        $totalSales = (float) DB::table('sales_records')->sum('amount');
        $commissionsPaid = (float) DB::table('sales_records')->whereNotNull('commission_amount')->sum('commission_amount');
        $pendingApprovals = DB::table('sales_records')->where('is_verified', false)->count();
        $activeAgents = DB::table('sales_records')->distinct('employee_id')->count('employee_id');

        $verifiedCommission = (float) DB::table('sales_records')->where('is_verified', true)->sum('commission_amount');
        $pendingCommission = (float) DB::table('sales_records')->where('is_verified', false)->sum('commission_amount');

        $commissionData = [
            ['Verified Commission', $verifiedCommission],
            ['Pending Commission', $pendingCommission],
        ];

        $reports = DB::table('sales_records')
            ->leftJoin('employees', 'sales_records.employee_id', '=', 'employees.id')
            ->leftJoin('stores', 'sales_records.store_id', '=', 'stores.id')
            ->select(
                'sales_records.sale_date as date',
                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as user_name"),
                'stores.name as store_name',
                'sales_records.amount as sales',
                'sales_records.commission_amount as commission',
                'sales_records.is_verified'
            )
            ->orderBy('sales_records.sale_date', 'desc')
            ->limit(12)
            ->get()
            ->map(function ($row) {
                return (object) [
                    'date' => Carbon::parse($row->date)->format('Y-m-d'),
                    'user_name' => trim($row->user_name) ?: '-',
                    'store_name' => $row->store_name ?: '-',
                    'sales' => (float) $row->sales,
                    'commission' => (float) ($row->commission ?? 0),
                    'status' => $row->is_verified ? 'Verified' : 'Pending',
                ];
            });

        return view('reports.index', compact(
            'totalSales',
            'commissionsPaid',
            'pendingApprovals',
            'activeAgents',
            'months',
            'salesData',
            'commissionData',
            'reports'
        ));
    }
}

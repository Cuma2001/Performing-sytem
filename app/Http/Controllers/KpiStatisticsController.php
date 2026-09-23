<?php

namespace App\Http\Controllers;

use App\Services\KpiDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KpiStatisticsController extends Controller
{
    public function __construct(private readonly KpiDashboardService $dashboard)
    {
    }

    public function index()
    {
        return view('statistics.kpi', [
            'options' => $this->dashboard->options(auth()->user()),
            'defaultYear' => now()->year,
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboard->dashboard(auth()->user(), $this->filters($request)),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $data = $this->dashboard->dashboard(auth()->user(), $this->filters($request));

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Store Name', 'Actual', 'Target', 'Variance', 'Score', 'Status']);
            foreach ($data['summary_rows'] as $row) {
                fputcsv($handle, [$row['name'], $row['actual'], $row['target'], $row['variance'], $row['score'], $row['status']]);
            }
            fclose($handle);
        }, 'kpi-performance-summary.csv', ['Content-Type' => 'text/csv']);
    }

    private function filters(Request $request): array
    {
        $filters = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'quarter' => ['nullable', 'integer', 'between:1,4'],
            'month' => ['nullable', 'integer', 'between:1,12'],
            'store_id' => ['nullable', 'integer', 'min:1'],
            'region' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'in:Salesperson,Supervisor'],
            'user_id' => ['nullable', 'integer', 'min:1'],
            'kpi' => ['nullable', 'string', 'max:100'],
            'level' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:achieved,on_target,below_target,critical,pending'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $filters['year'] ??= now()->year;

        return $filters;
    }
}
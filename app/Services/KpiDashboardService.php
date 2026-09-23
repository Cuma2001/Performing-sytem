<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KpiDashboardService
{
    private const STATUS_LABELS = [
        'achieved' => 'Achieved',
        'on_target' => 'On Target',
        'below_target' => 'Below Target',
        'critical' => 'Critical',
        'pending' => 'Pending',
    ];

    public function options(User $user): array
    {
        $query = $this->baseQuery($user);
        $availableStoreIds = (clone $query)->whereNotNull('sp.store_id')->pluck('sp.store_id')->unique()->values();
        $availableRegionIds = (clone $query)->whereNotNull('s.region_id')->pluck('s.region_id')->unique()->values();

        return [
            'years' => (clone $query)->select('sp.year')->distinct()->orderByDesc('sp.year')->pluck('year')->values(),
            'regions' => DB::table('regions')->whereIn('id', $availableRegionIds)->orderBy('name')->pluck('name')->values(),
            'stores' => (clone $query)->select('sp.store_id', 'sp.store_code', 's.name')
                ->whereNotNull('sp.store_id')->distinct()->orderBy('s.name')->get(),
            'kpis' => (clone $query)->whereNotNull('sp.kpi')->distinct()->orderBy('sp.kpi')->pluck('kpi')->values(),
            'levels' => (clone $query)->whereNotNull('sp.business_unit')->distinct()->orderBy('sp.business_unit')->pluck('business_unit')->values(),
            'roles' => DB::table('roles')->select('name')->whereIn('name', ['Salesperson', 'Supervisor'])->orderBy('name')->pluck('name')->values(),
            'users' => DB::table('users as u')
                ->leftJoin('roles as ur', 'ur.id', '=', 'u.role_id')
                ->select('u.id', 'u.name', 'u.email', 'ur.name as role_name')
                ->where(function ($userQuery) use ($availableStoreIds, $availableRegionIds) {
                    $userQuery->whereIn('u.store_id', $availableStoreIds)
                        ->orWhereIn('u.region_id', $availableRegionIds);
                })
                ->orderBy('u.name')->get(),
        ];
    }

    public function dashboard(User $user, array $filters): array
    {
        $rows = $this->filteredRows($user, $filters);
        $categories = $rows->pluck('kpi')->filter()->unique()->sort()->values();
        $stores = $this->storeRanking($rows);
        $summary = $this->summary($rows, $categories);
        $totals = $this->totals($rows);
        $trend = $this->trend($rows);

        return [
            'summary' => $totals,
            'charts' => [
                'distribution' => $this->statusDistribution($rows),
                'weighting' => [],
                'actual_vs_target' => $this->actualVsTarget($rows, $categories),
                'trend' => $trend,
                'stores' => $stores,
                'store_kpis' => $this->storeKpis($rows, $categories),
                'achievement_trend' => $this->achievementTrend($rows, $trend),
                'status_by_store' => $this->statusByStore($rows),
            ],
            'categories' => $categories,
            'summary_rows' => $summary,
            'meta' => [
                'has_data' => $rows->isNotEmpty(),
                'weighting_available' => false,
                'period' => $this->periodLabel($filters),
            ],
        ];
    }

    private function baseQuery(User $user): Builder
    {
        $query = DB::table('store_performance as sp')
            ->leftJoin('stores as s', 's.id', '=', 'sp.store_id')
            ->select([
                'sp.store_id', 'sp.store_code', 'sp.kpi', 'sp.business_unit',
                'sp.year', 'sp.month', 'sp.target_amount', 'sp.actual_amount',
                's.name as store_name', 's.region_id', 'r.name as region_name',
            ]);
            $query->leftJoin('regions as r', 'r.id', '=', 's.region_id');

        $role = strtolower((string) ($user->role ?? ''));
        $roleName = strtolower((string) DB::table('roles')->where('id', $user->role_id)->value('name'));
        $canViewAll = in_array($role ?: $roleName, [
            'superadmin', 'admin', 'ceo/hr', 'ceo', 'hr', 'manager', 'chief executive officer',
        ], true);

        if (! $canViewAll) {
            $storeId = $user->store_id ?? $user->store ?? null;
            $query->where('sp.store_id', $storeId ?: 0);
        }

        return $query;
    }

    private function filteredRows(User $user, array $filters): Collection
    {
        $query = $this->baseQuery($user);

        if (! empty($filters['year'])) {
            $query->where('sp.year', (int) $filters['year']);
        }
        if (! empty($filters['quarter'])) {
            $months = [1 => [1, 3], 2 => [4, 6], 3 => [7, 9], 4 => [10, 12]][(int) $filters['quarter']];
            $query->whereBetween('sp.month', $months);
        }
        if (! empty($filters['month'])) {
            $query->where('sp.month', (int) $filters['month']);
        }
        if (! empty($filters['store_id'])) {
            $query->where('sp.store_id', (int) $filters['store_id']);
        }
        if (! empty($filters['region'])) {
            $query->where('r.name', $filters['region']);
        }
        if (! empty($filters['role'])) {
            $query->whereExists(function ($userQuery) use ($filters) {
                $userQuery->select(DB::raw(1))
                    ->from('users as uf')
                    ->leftJoin('roles as rf', 'rf.id', '=', 'uf.role_id')
                    ->where(function ($scope) {
                        $scope->whereColumn('uf.store_id', 'sp.store_id')
                            ->orWhereColumn('uf.region_id', 's.region_id');
                    })
                    ->where('rf.name', $filters['role']);
            });
        }
        if (! empty($filters['user_id'])) {
            $query->whereExists(function ($userQuery) use ($filters) {
                $userQuery->select(DB::raw(1))
                    ->from('users as uf')
                    ->where('uf.id', (int) $filters['user_id'])
                    ->where(function ($scope) {
                        $scope->whereColumn('uf.store_id', 'sp.store_id')
                            ->orWhereColumn('uf.region_id', 's.region_id');
                    });
            });
        }
        if (! empty($filters['kpi'])) {
            $query->where('sp.kpi', $filters['kpi']);
        }
        if (! empty($filters['level'])) {
            $query->where('sp.business_unit', $filters['level']);
        }
        if (! empty($filters['from'])) {
            $from = Carbon::parse($filters['from']);
            $query->where(function ($dateQuery) use ($from) {
                $dateQuery->where('sp.year', '>', $from->year)
                    ->orWhere(function ($sameYear) use ($from) {
                        $sameYear->where('sp.year', $from->year)->where('sp.month', '>=', $from->month);
                    });
            });
        }
        if (! empty($filters['to'])) {
            $to = Carbon::parse($filters['to']);
            $query->where(function ($dateQuery) use ($to) {
                $dateQuery->where('sp.year', '<', $to->year)
                    ->orWhere(function ($sameYear) use ($to) {
                        $sameYear->where('sp.year', $to->year)->where('sp.month', '<=', $to->month);
                    });
            });
        }

        return $query->orderBy('sp.year')->orderBy('sp.month')->get()
            ->map(function ($row) {
                $row->target = (float) ($row->target_amount ?? 0);
                $row->actual = (float) ($row->actual_amount ?? 0);
                $row->achievement = $row->target > 0 ? ($row->actual / $row->target) * 100 : 0;
                $row->variance = $row->actual - $row->target;
                $row->status = $this->status($row->achievement, $row->target);

                return $row;
            })
            ->when(! empty($filters['status']), fn ($items) => $items->where('status', $filters['status'])->values());
    }

    private function status(float $achievement, float $target): string
    {
        if ($target <= 0) return 'pending';
        if ($achievement >= 100) return 'achieved';
        if ($achievement >= 90) return 'on_target';
        if ($achievement >= 75) return 'below_target';

        return 'critical';
    }

    private function totals(Collection $rows): array
    {
        $target = $rows->sum('target');
        $actual = $rows->sum('actual');
        $achieved = $rows->where('status', 'achieved')->count();

        return [
            'overall_score' => $target > 0 ? round(($actual / $target) * 100, 1) : 0,
            'target_achievement' => $target > 0 ? round(($actual / $target) * 100, 1) : 0,
            'achieved' => $achieved,
            'total_kpis' => $rows->count(),
            'below_target' => $rows->whereIn('status', ['below_target', 'critical'])->count(),
            'total_stores' => $rows->pluck('store_id')->filter()->unique()->count(),
            'average_store_performance' => round($this->storeRanking($rows)->avg('score') ?? 0, 1),
            'top_store' => $this->storeRanking($rows)->first()['name'] ?? 'No data',
            'performance_change' => null,
        ];
    }

    private function statusDistribution(Collection $rows): array
    {
        return collect(self::STATUS_LABELS)->map(fn ($label, $status) => [
            'name' => $label,
            'y' => $rows->where('status', $status)->count(),
            'status' => $status,
        ])->values()->all();
    }

    private function actualVsTarget(Collection $rows, Collection $categories): array
    {
        return [
            'categories' => $categories->all(),
            'actual' => $categories->map(fn ($kpi) => round($rows->where('kpi', $kpi)->sum('actual'), 2))->all(),
            'target' => $categories->map(fn ($kpi) => round($rows->where('kpi', $kpi)->sum('target'), 2))->all(),
        ];
    }

    private function trend(Collection $rows): array
    {
        $groups = $rows->groupBy(fn ($row) => sprintf('%04d-%02d', $row->year, $row->month));

        return [
            'categories' => $groups->keys()->values()->all(),
            'actual' => $groups->map(fn ($items) => round($items->sum('actual'), 2))->values()->all(),
            'target' => $groups->map(fn ($items) => round($items->sum('target'), 2))->values()->all(),
        ];
    }

    private function achievementTrend(Collection $rows, array $trend): array
    {
        $groups = $rows->groupBy(fn ($row) => sprintf('%04d-%02d', $row->year, $row->month));

        return [
            'categories' => $trend['categories'],
            'achieved' => $groups->map(function ($items) {
                $target = $items->sum('target');
                return $target > 0 ? round(($items->sum('actual') / $target) * 100, 1) : 0;
            })->values()->all(),
            'target' => array_fill(0, count($trend['categories']), 100),
        ];
    }

    private function storeRanking(Collection $rows): Collection
    {
        return $rows->groupBy(fn ($row) => $row->store_id ?: $row->store_code)->map(function ($items) {
            $target = $items->sum('target');
            $actual = $items->sum('actual');

            return [
                'key' => $items->first()->store_id ?: $items->first()->store_code,
                'id' => $items->first()->store_id,
                'name' => $items->first()->store_name ?: $items->first()->store_code ?: 'Unassigned store',
                'score' => $target > 0 ? round(($actual / $target) * 100, 1) : 0,
                'target' => round($target, 2),
                'actual' => round($actual, 2),
                'variance' => round($actual - $target, 2),
            ];
        })->sortByDesc('score')->values();
    }

    private function storeKpis(Collection $rows, Collection $categories): array
    {
        $storeRows = $rows->groupBy(fn ($row) => $row->store_id ?: $row->store_code);

        return [
            'categories' => $categories->all(),
            'stores' => $storeRows->map(function ($items) use ($categories) {
                return [
                    'name' => $items->first()->store_name ?: $items->first()->store_code ?: 'Unassigned store',
                    'data' => $categories->map(function ($kpi) use ($items) {
                        $kpiRows = $items->where('kpi', $kpi);
                        $target = $kpiRows->sum('target');
                        return $target > 0 ? round(($kpiRows->sum('actual') / $target) * 100, 1) : 0;
                    })->values()->all(),
                ];
            })->values()->all(),
        ];
    }

    private function statusByStore(Collection $rows): array
    {
        return $rows->groupBy(fn ($row) => $row->store_id ?: $row->store_code)->map(function ($items) {
            return [
                'name' => $items->first()->store_name ?: $items->first()->store_code ?: 'Unassigned store',
                'achieved' => $items->where('status', 'achieved')->count(),
                'on_target' => $items->where('status', 'on_target')->count(),
                'below_target' => $items->where('status', 'below_target')->count(),
                'critical' => $items->where('status', 'critical')->count(),
            ];
        })->values()->all();
    }

    private function summary(Collection $rows, Collection $categories): array
    {
        return $this->storeRanking($rows)->map(function ($store) use ($rows, $categories) {
            $storeRows = $rows->filter(fn ($row) => ($row->store_id ?: $row->store_code) === $store['key']);
            $values = [];
            foreach ($categories as $category) {
                $items = $storeRows->where('kpi', $category);
                $target = $items->sum('target');
                $values[$category] = $target > 0 ? round(($items->sum('actual') / $target) * 100, 1) : 0;
            }

            return array_merge($store, ['kpis' => $values, 'status' => $this->status($store['score'], $store['target'])]);
        })->all();
    }

    private function periodLabel(array $filters): string
    {
        if (! empty($filters['from']) || ! empty($filters['to'])) return trim(($filters['from'] ?? 'Start') . ' - ' . ($filters['to'] ?? 'Today'));
        if (! empty($filters['year'])) return 'Year ' . $filters['year'];
        return 'All available periods';
    }
}
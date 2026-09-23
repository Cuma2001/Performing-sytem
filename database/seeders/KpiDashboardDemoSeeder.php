<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KpiDashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $year = 2026;
            $demoCodes = ['DEMO-HEM', 'DEMO-BEA', 'DEMO-STO', 'DEMO-VIN'];

            DB::table('store_performance')->whereIn('store_code', $demoCodes)->delete();
            DB::table('store_targets')->whereIn('store_code', $demoCodes)->delete();
            DB::table('stores')->whereIn('code', $demoCodes)->delete();
            DB::table('regions')->where('code', 'DEMO-REGION')->delete();

            $regionId = DB::table('regions')->insertGetId([
                'name' => 'Demo Region',
                'code' => 'DEMO-REGION',
                'description' => 'Isolated records for previewing the KPI dashboard.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $stores = [
                ['name' => 'Hemingways Demo', 'code' => 'DEMO-HEM', 'city' => 'East London'],
                ['name' => 'Beacon Bay Demo', 'code' => 'DEMO-BEA', 'city' => 'East London'],
                ['name' => 'Stone Towers Demo', 'code' => 'DEMO-STO', 'city' => 'East London'],
                ['name' => 'Vincent Park Demo', 'code' => 'DEMO-VIN', 'city' => 'East London'],
            ];

            $kpis = [
                ['name' => 'Sales', 'unit' => 'Sales'],
                ['name' => 'Customer Experience', 'unit' => 'Customer Experience'],
                ['name' => 'Revenue', 'unit' => 'Revenue'],
                ['name' => 'Market Share', 'unit' => 'Market Share'],
                ['name' => 'Operational Performance', 'unit' => 'Operations'],
            ];

            $baseTargets = [
                'Sales' => 100000,
                'Customer Experience' => 100,
                'Revenue' => 150000,
                'Market Share' => 100,
                'Operational Performance' => 100,
            ];
            $storeRatios = [
                'DEMO-HEM' => [1.08, 1.03, 1.12, 1.01, 1.05],
                'DEMO-BEA' => [0.98, 0.94, 1.01, 0.96, 0.99],
                'DEMO-STO' => [0.84, 0.78, 0.88, 0.82, 0.86],
                'DEMO-VIN' => [0.91, 0.89, 0.95, 0.93, 0.90],
            ];
            $monthlyMovement = [0.96, 0.99, 1.02, 1.04, 1.01, 1.06];

            foreach ($stores as $storeData) {
                $storeId = DB::table('stores')->insertGetId([
                    'region_id' => $regionId,
                    'name' => $storeData['name'],
                    'code' => $storeData['code'],
                    'region' => 'Demo Region',
                    'city' => $storeData['city'],
                    'country' => 'South Africa',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($kpis as $kpiIndex => $kpi) {
                    $baseTarget = $baseTargets[$kpi['name']];
                    $targetColumns = array_fill_keys([
                        'target_jan', 'target_feb', 'target_mar', 'target_apr', 'target_may', 'target_jun',
                        'target_jul', 'target_aug', 'target_sep', 'target_oct', 'target_nov', 'target_dec',
                    ], $baseTarget);

                    $targetId = DB::table('store_targets')->insertGetId(array_merge([
                        'store_code' => $storeData['code'],
                        'store_name' => $storeData['name'],
                        'ownership' => 'DEMO',
                        'store_type' => 'Demo',
                        'region' => 'Demo Region',
                        'cluster' => 'Demo Cluster',
                        'kpi' => $kpi['name'],
                        'business_unit' => $kpi['unit'],
                        'annual_budget' => $baseTarget * 12,
                        'target_year' => $year,
                        'is_active' => true,
                        'notes' => 'Dashboard preview data. Safe to delete by DEMO- store code.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ], $targetColumns));

                    foreach (range(1, 6) as $month) {
                        $target = $baseTarget;
                        $actual = round($target * $storeRatios[$storeData['code']][$kpiIndex] * $monthlyMovement[$month - 1], 2);

                        DB::table('store_performance')->insert([
                            'store_target_id' => $targetId,
                            'store_id' => $storeId,
                            'store_code' => $storeData['code'],
                            'kpi' => $kpi['name'],
                            'year' => $year,
                            'month' => $month,
                            'target_amount' => $target,
                            'actual_amount' => $actual,
                            'business_unit' => $kpi['unit'],
                            'notes' => 'Dashboard preview data.',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        });
    }
}
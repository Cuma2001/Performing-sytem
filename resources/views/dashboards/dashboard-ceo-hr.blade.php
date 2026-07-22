@extends('layouts.app')

@section('title', 'CEO/HR Dashboard')
@section('page-title', 'CEO/HR Dashboard')
@section('page-subtitle', 'Welcome back, ' . (auth()->user()->name ?? 'Guest'))

@section('content')
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-store" style="color: var(--primary-teal);"></i>
            </div>
            <h3>Total Stores</h3>
            <div class="stat-value">{{ $totalStores ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users" style="color: var(--primary-teal);"></i>
            </div>
            <h3>Total Users</h3>
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line" style="color: var(--primary-teal);"></i>
            </div>
            <h3>Company KPI Score</h3>
            <div class="stat-value">94.6%</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign" style="color: var(--primary-teal);"></i>
            </div>
            <h3>Total Revenue</h3>
            <div class="stat-value">R {{ number_format($totalRevenue ?? 0, 2) }}</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="chart-grid">
        <div class="chart-card">
            <h3>KPI Distribution</h3>
            <div id="companyKPIPie" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3>KPI Weightage</h3>
            <div id="weightageChart" class="chart-container"></div>
        </div>
    </div>

    <!-- KPI Comparison Section -->
    <div class="chart-card" style="margin-bottom: 32px;">
        <h3>SMS Mobile vs MTN KPI Comparison</h3>
        <div id="kpiComparisonChart" class="chart-container" style="height: 400px;"></div>
    </div>

    <!-- Performance Sections -->
    <div class="chart-grid">
        <div class="chart-card">
            <h3>Company Performance Trend</h3>
            <div id="performanceTrendChart" class="chart-container"></div>
        </div>

        <div class="chart-card">
            <h3>Store Performance Ranking</h3>
            <div id="storeRankingChart" class="chart-container"></div>
        </div>
    </div>

    <!-- KPI Summary by Store -->
    <div class="chart-card">
        <h3>KPI Summary by Store</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Store Name</th>
                        <th>Sales KPI</th>
                        <th>General KPI</th>
                        <th>Overall Score</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Hemmingway's</strong></td>
                        <td>98%</td>
                        <td>92%</td>
                        <td>104.3%</td>
                        <td style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check"></i> Exceeded</td>
                    </tr>
                    <tr>
                        <td><strong>Beacon Bay</strong></td>
                        <td>95%</td>
                        <td>88%</td>
                        <td>98.7%</td>
                        <td style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check"></i> Met</td>
                    </tr>
                    <tr>
                        <td><strong>Stone Towers</strong></td>
                        <td>87%</td>
                        <td>79%</td>
                        <td>91.2%</td>
                        <td style="color: #f4c610; font-weight: 600;"><i class="fas fa-exclamation-triangle"></i> Below Target</td>
                    </tr>
                    <tr>
                        <td><strong>Vincent Park</strong></td>
                        <td>90%</td>
                        <td>85%</td>
                        <td>95.1%</td>
                        <td style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check"></i> Met</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('companyKPIPie', {
            chart: { type: 'pie', backgroundColor: 'transparent', options3d: { enabled: true, alpha: 45 } },
            title: { text: 'Company KPI Distribution' },
            series: [{
                name: 'KPIs',
                data: [
                    { name: 'Sales KPIs', y: 80, color: '#1d6988' },
                    { name: 'General KPIs', y: 20, color: '#f4c610' }
                ]
            }],
            credits: { enabled: false }
        });

        Highcharts.chart('weightageChart', {
            chart: { type: 'bar', backgroundColor: 'transparent' },
            title: { text: 'KPI Weightage by Level' },
            xAxis: { categories: ['Company', 'Store', 'Supervisor', 'Individual'] },
            yAxis: { title: { text: 'Weightage (%)' } },
            series: [{ name: 'Weight', data: [100, 85, 70, 50], color: '#e5222b' }],
            credits: { enabled: false }
        });

        Highcharts.chart('kpiComparisonChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: 'SMS Mobile vs MTN KPI Benchmark', style: { fontWeight: 'bold', color: '#1e2f3f' } },
            xAxis: { categories: ['Sales KPI', 'Customer Experience', 'Retention Rate', 'Fibre Sales', 'Overall Score'] },
            yAxis: {
                title: { text: 'Score (%)' },
                labels: { format: '{value}%' },
                max: 100
            },
            plotOptions: {
                column: {
                    borderRadius: 5,
                    pointPadding: 0.2,
                    groupPadding: 0.1
                }
            },
            series: [
                {
                    name: 'SMS Mobile',
                    data: [95, 90, 80, 85, 94.6],
                    color: '#1d6988'
                },
                {
                    name: 'MTN Benchmark',
                    data: [92, 88, 75, 80, 90],
                    color: '#f4c610'
                }
            ],
            credits: { enabled: false },
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                itemDistance: 20
            }
        });

        Highcharts.chart('performanceTrendChart', {
            chart: { type: 'line', backgroundColor: 'transparent' },
            title: { text: 'Quarterly Performance Trend', style: { fontWeight: 'bold', color: '#1e2f3f' } },
            xAxis: { categories: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025', 'Q1 2026', 'Q2 2026'] },
            yAxis: {
                title: { text: 'KPI Score (%)' },
                labels: { format: '{value}%' },
                max: 110
            },
            series: [
                {
                    name: 'Company Avg',
                    data: [78, 83, 86, 89, 92, 95],
                    color: '#1d6988',
                    marker: {
                        fillColor: '#1d6988',
                        radius: 5
                    }
                },
                {
                    name: 'Top Store',
                    data: [82, 87, 90, 94, 98, 104],
                    color: '#f4c610',
                    marker: {
                        fillColor: '#f4c610',
                        radius: 5
                    }
                }
            ],
            credits: { enabled: false }
        });

        Highcharts.chart('storeRankingChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: 'Store Performance Ranking', style: { fontWeight: 'bold', color: '#1e2f3f' } },
            xAxis: { categories: ['Hemmingway\'s', 'Beacon Bay', 'Stone Towers', 'Vincent Park'] },
            yAxis: {
                title: { text: 'Overall Score (%)' },
                labels: { format: '{value}%' },
                max: 150
            },
            series: [
                {
                    name: 'Store Score',
                    data: [104.3, 98.7, 91.2, 95.1],
                    color: '#1d6988'
                }
            ],
            credits: { enabled: false },
            legend: {
                enabled: true,
                align: 'center',
                verticalAlign: 'bottom'
            },
            plotOptions: {
                column: {
                    borderRadius: 5
                }
            }
        });
    </script>
@endsection

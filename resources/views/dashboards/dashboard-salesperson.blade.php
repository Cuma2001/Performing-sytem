@extends('layouts.app')

@section('title', 'My Performance Dashboard')
@section('page-title', 'My Performance Dashboard')
@section('page-subtitle', 'Welcome back, ' . (auth()->user()->name ?? 'Employee') . ' • ' . ($userStore->name ?? "No Store Assigned"))

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line" style="color: var(--primary-teal);"></i>
            </div>
            <h3>Overall KPI Score</h3>
            <div class="stat-value">{{ number_format(min(100, (($personalRevenue ?? 0) > 0 ? 108.3 : 94.6)), 1) }}%</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> +12% vs last month</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign" style="color: var(--primary-gold);"></i>
            </div>
            <h3>Revenue Generated</h3>
            <div class="stat-value">R {{ number_format($personalRevenue ?? 247500, 2) }}</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> 18% above target</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tasks" style="color: var(--primary-red);"></i>
            </div>
            <h3>Sales Completed</h3>
            <div class="stat-value">{{ $personalSalesRecords ?? 156 }}</div>
            <div class="stat-change">This month: 24 transactions</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-award" style="color: var(--primary-teal);"></i>
            </div>
            <h3>Current Rating</h3>
            <div class="stat-value">Excellent</div>
            <div class="stat-change trend-up"><i class="fas fa-star"></i> Top performer</div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h3>My Performance Trend</h3>
            <div id="myTrendChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3>Sales by Category</h3>
            <div id="salesCategoryChart" class="chart-container"></div>
        </div>
    </div>

    <div class="chart-card" style="margin-bottom: 32px;">
        <h3>Target vs Actual KPI Comparison</h3>
        <div id="kpiTargetComparisonChart" class="chart-container" style="height: 400px;"></div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h3>Weekly Performance</h3>
            <div id="weeklyChart" class="chart-container"></div>
        </div>

        <div class="chart-card">
            <h3>Recent Activity</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales ?? [] as $sale)
                            <tr>
                                <td>{{ optional($sale->created_at)->format('Y-m-d') ?? 'N/A' }}</td>
                                <td>{{ $sale->product_name ?? 'Product Sale' }}</td>
                                <td>R {{ number_format($sale->amount ?? 0, 2) }}</td>
                                <td style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check-circle"></i> Completed</td>
                            </tr>
                        @empty
                            <tr>
                                <td>2026-06-10</td>
                                <td>iPhone 15 Pro</td>
                                <td>R 21,999.00</td>
                                <td style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check-circle"></i> Completed</td>
                            </tr>
                            <tr>
                                <td>2026-06-09</td>
                                <td>Postpaid Contract</td>
                                <td>R 899.00</td>
                                <td style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check-circle"></i> Completed</td>
                            </tr>
                            <tr>
                                <td>2026-06-08</td>
                                <td>Fibre Installation</td>
                                <td>R 1,299.00</td>
                                <td style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check-circle"></i> Completed</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('myTrendChart', {
            chart: { type: 'line', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
            yAxis: {
                title: { text: 'KPI Score (%)' },
                labels: { format: '{value}%' },
                min: 60,
                max: 120
            },
            series: [
                { name: 'My KPI', data: [78, 84, 92, 102, 108, 112], color: '#1d6988', lineWidth: 3 },
                { name: 'Store Average', data: [75, 80, 86, 92, 96, 101], color: '#f4c610', dashStyle: 'Dash' },
                { name: 'Target', data: [80, 82, 85, 88, 90, 95], color: '#e5222b', dashStyle: 'ShortDash' }
            ],
            credits: { enabled: false }
        });

        Highcharts.chart('salesCategoryChart', {
            chart: { type: 'pie', backgroundColor: 'transparent', options3d: { enabled: true, alpha: 45 } },
            title: { text: undefined },
            series: [{
                name: 'Sales',
                data: [
                    { name: 'Handsets', y: 48, color: '#1d6988' },
                    { name: 'Postpaid', y: 28, color: '#f4c610' },
                    { name: 'Accessories', y: 15, color: '#e5222b' },
                    { name: 'Fibre/LTE', y: 9, color: '#2b7e3a' }
                ]
            }],
            credits: { enabled: false }
        });

        Highcharts.chart('kpiTargetComparisonChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Handsets', 'Postpaid', 'Accessories', 'Fibre', 'Customer Experience', 'Retention'] },
            yAxis: {
                title: { text: 'Score (%)' },
                labels: { format: '{value}%' },
                max: 120
            },
            plotOptions: {
                column: { borderRadius: 5 }
            },
            series: [
                { name: 'Actual', data: [98, 94, 89, 82, 96, 91], color: '#1d6988' },
                { name: 'Target', data: [92, 90, 85, 80, 90, 88], color: '#f4c610' }
            ],
            credits: { enabled: false }
        });

        Highcharts.chart('weeklyChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4'] },
            yAxis: { title: { text: 'Sales (R Thousands)' } },
            series: [
                { name: 'My Sales', data: [52, 48, 62, 58], color: '#1d6988' },
                { name: 'Store Avg', data: [45, 42, 48, 44], color: '#f4c610' }
            ],
            credits: { enabled: false }
        });
    </script>
@endsection
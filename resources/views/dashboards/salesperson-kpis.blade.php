@extends('layouts.app')

@section('title', 'Salesperson KPI Dashboard')
@section('page-title', 'Salesperson KPI Dashboard')
@section('page-subtitle', 'Welcome back, ' . (auth()->user()->name ?? 'Employee') . ' • ' . ($userStore->name ?? 'No Store Assigned'))

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-line" style="color: var(--primary-teal);"></i></div>
            <h3>Revenue KPI</h3>
            <div class="stat-value">122.3%</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> Exceeding target</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-check" style="color: var(--primary-gold);"></i></div>
            <h3>Conversion KPI</h3>
            <div class="stat-value">109.3%</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> Above target</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-handshake" style="color: var(--primary-red);"></i></div>
            <h3>Retention KPI</h3>
            <div class="stat-value">104.4%</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> On track</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-broadcast-tower" style="color: var(--primary-teal);"></i></div>
            <h3>Fibre KPI</h3>
            <div class="stat-value">83.3%</div>
            <div class="stat-change trend-down"><i class="fas fa-arrow-down"></i> Needs focus</div>
        </div>
    </div>

    <div class="chart-card" style="margin-bottom: 32px;">
        <h3>KPI Performance Overview</h3>
        <div id="salespersonKpiChart" class="chart-container" style="height: 360px;"></div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h3>KPI Achievement by Metric</h3>
            <div id="kpiAchievementChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3>KPI Summary</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>KPI</th>
                            <th>Target</th>
                            <th>Actual</th>
                            <th>Achievement</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kpis as $kpi)
                            <tr>
                                <td><strong>{{ $kpi['name'] }}</strong></td>
                                <td>{{ $kpi['target'] }}</td>
                                <td>{{ $kpi['actual'] }}</td>
                                <td>{{ $kpi['achievement'] }}%</td>
                                <td>
                                    @if($kpi['trend'] === 'up')
                                        <span style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check-circle"></i> {{ $kpi['status'] }}</span>
                                    @else
                                        <span style="color: #d97706; font-weight: 600;"><i class="fas fa-exclamation-triangle"></i> {{ $kpi['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('salespersonKpiChart', {
            chart: { type: 'line', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
            yAxis: {
                title: { text: 'Achievement (%)' },
                labels: { format: '{value}%' },
                min: 60,
                max: 140
            },
            series: [
                { name: 'Sales Revenue', data: [92, 96, 105, 110, 118, 122], color: '#1d6988', lineWidth: 3 },
                { name: 'Conversion', data: [88, 90, 94, 101, 106, 109], color: '#f4c610', dashStyle: 'Dash' },
                { name: 'Retention', data: [86, 89, 94, 96, 101, 104], color: '#2b7e3a', dashStyle: 'ShortDash' }
            ],
            credits: { enabled: false }
        });

        Highcharts.chart('kpiAchievementChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Revenue', 'Conversion', 'Retention', 'Fibre', 'Upsell'] },
            yAxis: {
                title: { text: 'Achievement (%)' },
                labels: { format: '{value}%' },
                max: 130
            },
            series: [{
                name: 'Actual vs Target',
                data: [122, 109, 104, 83, 113],
                color: '#1d6988'
            }],
            credits: { enabled: false }
        });
    </script>
@endsection

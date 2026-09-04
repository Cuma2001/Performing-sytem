@extends('layouts.app')

@section('title', 'Salesperson Targets')
@section('page-title', 'Salesperson Targets')
@section('page-subtitle', 'Welcome back, ' . (auth()->user()->name ?? 'Employee') . ' • ' . ($userStore->name ?? 'No Store Assigned'))

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-dollar-sign" style="color: var(--primary-teal);"></i></div>
            <h3>Monthly Revenue</h3>
            <div class="stat-value">R 146,800</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> 122% of target</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-bar" style="color: var(--primary-gold);"></i></div>
            <h3>Average Sale Value</h3>
            <div class="stat-value">R 3,640</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> 113% of target</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users" style="color: var(--primary-red);"></i></div>
            <h3>New Customers</h3>
            <div class="stat-value">58</div>
            <div class="stat-change trend-down"><i class="fas fa-arrow-down"></i> 89% of target</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-percent" style="color: var(--primary-teal);"></i></div>
            <h3>Upsell Rate</h3>
            <div class="stat-value">34%</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> 113% of target</div>
        </div>
    </div>

    <div class="chart-card" style="margin-bottom: 32px;">
        <h3>Target Progress Overview</h3>
        <div id="targetProgressChart" class="chart-container" style="height: 360px;"></div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h3>Current Target Status</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Target</th>
                            <th>Goal</th>
                            <th>Actual</th>
                            <th>Progress</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($targets as $target)
                            <tr>
                                <td><strong>{{ $target['name'] }}</strong></td>
                                <td>{{ $target['target'] }}</td>
                                <td>{{ $target['actual'] }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px; min-width: 150px;">
                                        <div style="width: 120px; height: 8px; background: #e2e8f0; border-radius: 12px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ min($target['progress'], 100) }}%; background: {{ $target['progress'] >= 100 ? '#2b7e3a' : '#1d6988' }}; border-radius: 12px;"></div>
                                        </div>
                                        <span>{{ $target['progress'] }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if($target['progress'] >= 100)
                                        <span style="color: #2b7e3a; font-weight: 600;"><i class="fas fa-check-circle"></i> {{ $target['status'] }}</span>
                                    @elseif($target['progress'] >= 90)
                                        <span style="color: #d97706; font-weight: 600;"><i class="fas fa-exclamation-triangle"></i> {{ $target['status'] }}</span>
                                    @else
                                        <span style="color: #e5222b; font-weight: 600;"><i class="fas fa-times-circle"></i> {{ $target['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="chart-card">
            <h3>Target Breakdown</h3>
            <div id="targetBreakdownChart" class="chart-container"></div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('targetProgressChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Revenue', 'Avg Sale', 'New Customers', 'Upsell'] },
            yAxis: { title: { text: 'Progress (%)' }, max: 130 },
            series: [{
                name: 'Target Progress',
                data: [122, 113, 89, 113],
                color: '#1d6988'
            }],
            credits: { enabled: false }
        });

        Highcharts.chart('targetBreakdownChart', {
            chart: { type: 'pie', backgroundColor: 'transparent', options3d: { enabled: true, alpha: 45 } },
            title: { text: undefined },
            series: [{
                name: 'Target share',
                data: [
                    { name: 'Revenue', y: 40, color: '#1d6988' },
                    { name: 'Avg Sale', y: 25, color: '#f4c610' },
                    { name: 'New Customers', y: 20, color: '#e5222b' },
                    { name: 'Upsell', y: 15, color: '#2b7e3a' }
                ]
            }],
            credits: { enabled: false }
        });
    </script>
@endsection

@extends('layouts.app')

@section('title', 'Supervisor Dashboard - Performance Management System')
@section('page-title', 'Supervisor Performance Hub')
@section('page-subtitle', 'Welcome, ' . (auth()->user()->name ?? 'Guest') . ' • Store: ' . ($userStore->name ?? 'No Store Assigned'))

@section('content')
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-line" style="color: var(--primary-teal);"></i></div>
            <h3>Store KPI Score</h3>
            <div class="stat-value">{{ $storePerformance->kpi_score ?? '92.5' }}%</div>
            <div class="stat-change {{ ($storePerformance->kpi_score ?? 92.5) > 90 ? 'trend-up' : 'trend-down' }}">
                <i class="fas fa-arrow-{{ ($storePerformance->kpi_score ?? 92.5) > 90 ? 'up' : 'down' }}"></i> 
                {{ ($storePerformance->kpi_score ?? 92.5) > 90 ? 'Above' : 'Below' }} target
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-bullseye" style="color: var(--primary-gold);"></i></div>
            <h3>Monthly Target</h3>
            <div class="stat-value">R {{ number_format($storeTarget->monthly_target ?? 500000, 0) }}</div>
            <div class="stat-change">Progress: {{ $storePerformance->progress ?? 78 }}%</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users" style="color: var(--primary-red);"></i></div>
            <h3>Team Members</h3>
            <div class="stat-value">{{ $teamMembers ?? 8 }}</div>
            <div class="stat-change">Active: {{ $activeTeamMembers ?? 8 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-trophy" style="color: var(--primary-teal);"></i></div>
            <h3>Store Rank</h3>
            <div class="stat-value">#{{ $storeRank ?? 2 }}</div>
            <div class="stat-change">Out of {{ $totalStores ?? 5 }} stores</div>
        </div>
    </div>

    <!-- Alerts Section -->
    <div class="chart-card" style="margin-bottom: 24px; background: #fef9e6; border-left: 4px solid var(--primary-red);">
        @if(isset($alerts) && count($alerts) > 0)
            <h4 style="color: var(--primary-red); margin-bottom: 12px;">
                <i class="fas fa-exclamation-triangle"></i> Action Required
            </h4>
            @foreach($alerts as $alert)
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <i class="fas fa-bell" style="color: var(--primary-gold);"></i>
                    <span>{{ $alert }}</span>
                </div>
            @endforeach
        @else
            <h4 style="color: var(--primary-teal); margin-bottom: 12px;">
                <i class="fas fa-check-circle"></i> Store Performance Summary
            </h4>
            <div style="display: flex; align-items: center; gap: 12px; padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.05);">
                <i class="fas fa-chart-line"></i>
                <span>Store is performing {{ ($storePerformance->kpi_score ?? 92.5) >= 90 ? 'above' : 'below' }} target. {{ ($storePerformance->kpi_score ?? 92.5) >= 90 ? 'Great job team!' : 'Focus needed on underperforming areas.' }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px; padding: 12px;">
                <i class="fas fa-users"></i>
                <span>Team attendance rate: {{ $attendanceRate ?? 95 }}% this month</span>
            </div>
        @endif
    </div>

    <!-- Charts -->
    <div class="chart-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Store Performance Trend (Last 6 Months)</h3>
            <div id="storeTrendChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> KPI Distribution - Store Performance</h3>
            <div id="kpiDistributionChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-simple"></i> Target vs Actual (Current Month)</h3>
            <div id="targetActualChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Weekly Sales Performance</h3>
            <div id="weeklySalesChart" class="chart-container"></div>
        </div>
    </div>

    <!-- Team Performance Table -->
    <div class="chart-card">
        <h3><i class="fas fa-users"></i> Team Performance Dashboard</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Team Member</th>
                        <th>Sales (R)</th>
                        <th>Transactions</th>
                        <th>KPI Score</th>
                        <th>Status</th>
                        <th>Target Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rank = 1; @endphp
                    @forelse($teamPerformance ?? [] as $member)
                        <tr>
                            <td>
                                @if($rank == 1) 🥇
                                @elseif($rank == 2) 🥈
                                @elseif($rank == 3) 🥉
                                @else {{ $rank }}
                                @endif
                            </td>
                            <td><strong>{{ $member->name }}</strong></td>
                            <td>R {{ number_format($member->total_revenue ?? 0, 2) }}</td>
                            <td>{{ $member->sales_count ?? 0 }}</td>
                            <td>{{ $member->kpi_score ?? rand(75, 105) }}%</td>
                            <td>
                                @php
                                    $score = $member->kpi_score ?? 75;
                                    $statusClass = $score >= 100 ? 'status-excellent' : ($score >= 85 ? 'status-good' : ($score >= 70 ? 'status-warning' : 'status-poor'));
                                    $statusText = $score >= 100 ? 'Excellent' : ($score >= 85 ? 'Good' : ($score >= 70 ? 'At Risk' : 'Poor'));
                                @endphp
                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 100px; height: 8px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
                                        <div style="height: 100%; width: {{ min($member->total_revenue / 1000, 100) }}%; background: var(--primary-teal); border-radius: 10px; transition: width 0.5s;"></div>
                                    </div>
                                    <span>{{ round(($member->total_revenue / 1000), 0) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @php $rank++; @endphp
                    @empty
                        <tr><td colspan="7" style="text-align: center;">No team members found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions & Insights -->
    <div class="chart-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Top Performers - Product Categories</h3>
            <div id="topProductsChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-calendar-alt"></i> Quick Actions</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 20px;">
                <button style="background: var(--primary-teal); color: white; padding: 12px; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; border: none; font-weight: 600;" onclick="location.href='{{ route('utility.master-upload') }}'">
                    <i class="fas fa-upload"></i> Upload KPIs
                </button>
                <button style="background: var(--primary-teal); color: white; padding: 12px; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; border: none; font-weight: 600;" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Report
                </button>
                <button style="background: var(--primary-teal); color: white; padding: 12px; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; border: none; font-weight: 600;" onclick="location.href='{{ route('profile.edit') }}'">
                    <i class="fas fa-user-edit"></i> Update Profile
                </button>
                <button style="background: var(--primary-teal); color: white; padding: 12px; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; border: none; font-weight: 600;" id="refreshData">
                    <i class="fas fa-sync-alt"></i> Refresh Data
                </button>
            </div>
            <div style="margin-top: 24px; padding: 16px; background: #f8fafc; border-radius: 16px;">
                <h4 style="color: var(--primary-teal); margin-bottom: 12px;">
                    <i class="fas fa-lightbulb"></i> Insights & Recommendations
                </h4>
                <ul style="list-style: none;">
                    <li style="margin-bottom: 8px;">✓ <strong>Top Performer:</strong> Focus on coaching strategies from top team member</li>
                    <li style="margin-bottom: 8px;">✓ <strong>Improvement Area:</strong> Fiber sales need attention - down 15% this month</li>
                    <li>✓ <strong>Training Opportunity:</strong> Retention KPI workshop scheduled for next week</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        // Chart 1: Store Performance Trend
        Highcharts.chart('storeTrendChart', {
            chart: { type: 'line', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
            yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' }, min: 60 },
            series: [
                { name: '{{ $userStore->name ?? "Store" }} Performance', data: [82, 85, 88, 91, 94, 96], color: '#1d6988', lineWidth: 3 },
                { name: 'Company Average', data: [78, 80, 83, 86, 89, 91], color: '#f4c610', dashStyle: 'Dash' },
                { name: 'Target', data: [80, 80, 85, 85, 90, 95], color: '#e5222b', dashStyle: 'ShortDash' }
            ],
            credits: { enabled: false }
        });

        // Chart 2: KPI Distribution
        Highcharts.chart('kpiDistributionChart', {
            chart: { type: 'pie', backgroundColor: 'transparent', options3d: { enabled: true, alpha: 45 } },
            title: { text: undefined },
            series: [{
                name: 'KPI Categories',
                data: [
                    { name: 'Sales KPIs', y: 80, color: '#1d6988' },
                    { name: 'Customer Experience', y: 8, color: '#f4c610' },
                    { name: 'Stock Management', y: 5, color: '#e5222b' },
                    { name: 'Compliance', y: 4, color: '#2b7e3a' },
                    { name: 'HR Metrics', y: 3, color: '#e67e22' }
                ]
            }],
            credits: { enabled: false }
        });

        // Chart 3: Target vs Actual
        Highcharts.chart('targetActualChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Sales', 'Retention', 'Customer Exp', 'Fiber', 'Accessories'] },
            yAxis: { title: { text: 'Achievement (%)' }, labels: { format: '{value}%' } },
            series: [
                { name: 'Actual', data: [98, 86, 92, 78, 112], color: '#e5222b' },
                { name: 'Target', data: [100, 95, 90, 85, 100], color: '#1d6988' }
            ],
            credits: { enabled: false }
        });

        // Chart 4: Weekly Sales Performance
        Highcharts.chart('weeklySalesChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4'] },
            yAxis: { title: { text: 'Sales (R Thousands)' } },
            series: [
                { name: 'This Month', data: [112, 98, 124, 108], color: '#1d6988' },
                { name: 'Last Month', data: [95, 102, 88, 115], color: '#f4c610' }
            ],
            credits: { enabled: false }
        });

        // Chart 5: Top Products
        Highcharts.chart('topProductsChart', {
            chart: { type: 'bar', backgroundColor: 'transparent' },
            title: { text: undefined },
            xAxis: { categories: ['Handsets', 'Postpaid', 'Accessories', 'Fiber', 'Airtime'] },
            yAxis: { title: { text: 'Sales (R Thousands)' } },
            series: [{
                name: 'Revenue',
                data: [245, 189, 98, 67, 54],
                color: '#1d6988',
                dataLabels: { enabled: true, format: 'R{point.y}K' }
            }],
            credits: { enabled: false }
        });

        // Refresh data button
        document.getElementById('refreshData')?.addEventListener('click', function() {
            location.reload();
        });

        // Auto-refresh every 5 minutes (300,000 ms)
        setInterval(function() {
            location.reload();
        }, 300000);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

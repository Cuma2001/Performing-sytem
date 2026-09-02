@extends('layouts.app')

@section('title', 'Reports')

@section('content')

<div class="page-wrapper">

    
    <div class="page-hero">
        <div>
            <div class="page-hero__eyebrow">
                <i class="fas fa-chart-line"></i> Reports Overview
            </div>
            <div class="page-hero__title">
                Performance Reports
            </div>
            <p class="page-hero__text">
                View detailed analytics, performance metrics, and system reports.
            </p>
        </div>

        <div class="page-hero__meta">
            <div class="info-pill">Monthly</div>
            <div class="info-pill">Updated Live</div>
        </div>
    </div>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <h4>Total Sales</h4>
            <h2>R {{ number_format($totalSales ?? 0) }}</h2>
        </div>

        <div class="stat-card">
            <h4>Commissions Paid</h4>
            <h2>R {{ number_format($commissionsPaid ?? 0) }}</h2>
        </div>

        <div class="stat-card">
            <h4>Pending Approvals</h4>
            <h2>{{ $pendingApprovals ?? 0 }}</h2>
        </div>

        <div class="stat-card">
            <h4>Active Agents</h4>
            <h2>{{ $activeAgents ?? 0 }}</h2>
        </div>
    </div>

    <!-- SALES CHART -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Monthly Sales Trends</div>
            <div class="card-subtitle">Sales performance over time</div>
        </div>
        <div class="card-body">
            <div id="salesChart"></div>
        </div>
    </div>

    <!-- COMMISSION CHART -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Commission Distribution</div>
            <div class="card-subtitle">Breakdown of commissions</div>
        </div>
        <div class="card-body">
            <div id="commissionChart"></div>
        </div>
    </div>

    <!-- REPORT TABLE -->
    <div class="card">
        <div class="card-header card-header--flex">
            <div>
                <div class="card-title">Recent Reports</div>
                <div class="card-subtitle">Latest system activity</div>
            </div>

            <div class="table-actions">
                <button class="logout-btn">Export PDF</button>
                <button class="logout-btn">Export Excel</button>
            </div>
        </div>

        <div class="card-body">
            <table class="table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Store</th>
                        <th>Sales</th>
                        <th>Commission</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                        @forelse($reports ?? [] as $report)
                        <tr>
                            <td>{{ $report->date }}</td>
                            <td>{{ $report->user_name }}</td>
                            <td>{{ $report->store_name }}</td>
                            <td>R {{ number_format($report->sales, 2) }}</td>
                            <td>R {{ number_format($report->commission, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($report->status) == 'verified' ? 'success' : 'warning' }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection


@section('scripts')
<script>
    // SALES CHART
    Highcharts.chart('salesChart', {
        chart: { type: 'line' },
        title: { text: '' },
        xAxis: {
            categories: {!! json_encode($months ?? []) !!}
        },
        yAxis: {
            title: { text: 'Sales (R)' }
        },
        series: [{
            name: 'Sales',
            data: {!! json_encode($salesData ?? []) !!}
        }]
    });

    // COMMISSION PIE
    Highcharts.chart('commissionChart', {
        chart: { type: 'pie' },
        title: { text: '' },
        series: [{
            name: 'Commission',
            data: {!! json_encode($commissionData ?? []) !!}
        }]
    });
</script>
@endsection
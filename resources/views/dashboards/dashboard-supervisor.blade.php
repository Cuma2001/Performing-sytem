@extends('layouts.app')

@section('title', 'Supervisor Dashboard - Performance Management System')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-red: #e5222b;
            --primary-gold: #f4c610;
            --primary-teal: #1d6988;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 20px;
        }
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border-top: 4px solid var(--primary-gold);
        }
        .stat-card h3 {
            color: var(--primary-teal);
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 800;
        }
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
        }
        .chart-container {
            height: 350px;
        }
        .alert-box {
            background: #fef9e6;
            border-left: 4px solid var(--primary-red);
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div style="background: white; border-radius: 20px; padding: 24px; margin-bottom: 24px;">
        <h1 style="color: var(--primary-teal); margin: 0 0 8px 0;">
            <i class="fas fa-clipboard-list" style="color: var(--primary-red);"></i> 
            Supervisor Performance Hub
        </h1>
        <p>Welcome, <strong>{{ auth()->user()->name }}</strong> • Store: {{ $userStore->name ?? 'N/A' }}</p>
        <p>Team Size: {{ $teamMembers }} members | Role: Supervisor</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3><i class="fas fa-chart-line"></i> Store KPI Score</h3>
            <div class="stat-value" style="color: var(--primary-teal);">
                {{ $storePerformance->kpi_score ?? '85.5' }}%
            </div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-bullseye"></i> Monthly Target</h3>
            <div class="stat-value" style="color: var(--primary-gold);">
                R {{ number_format($storeTarget->monthly_target ?? 500000, 2) }}
            </div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-users"></i> Team Members</h3>
            <div class="stat-value" style="color: var(--primary-red);">
                {{ $teamMembers }}
            </div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-trophy"></i> Store Rank</h3>
            <div class="stat-value">#2</div>
        </div>
    </div>

    @if(($storePerformance->kpi_score ?? 85.5) < 90)
    <div class="alert-box">
        <i class="fas fa-exclamation-triangle" style="color: var(--primary-red);"></i>
        <strong>Attention Required:</strong> Store KPI below 90%. Focus on retention and fibre sales improvement.
    </div>
    @endif

    <div class="chart-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Store Performance Trend</h3>
            <div id="storeTrend" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-users"></i> Team Performance</h3>
            <div id="teamChart" class="chart-container"></div>
        </div>
    </div>
</div>

<script>
    const brandTeal = '#1d6988';
    const brandGold = '#f4c610';
    
    Highcharts.chart('storeTrend', {
        chart: { type: 'line', backgroundColor: 'transparent' },
        title: { text: '{{ $userStore->name ?? "Your Store" }} Performance (Last 6 Months)' },
        xAxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
        yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' } },
        series: [{
            name: 'Store KPI',
            data: [82, 85, 88, 91, 94, 96],
            color: brandTeal,
            lineWidth: 3
        }, {
            name: 'Company Avg',
            data: [80, 82, 84, 86, 88, 90],
            color: brandGold,
            dashStyle: 'Dash'
        }],
        credits: { enabled: false }
    });
    
    Highcharts.chart('teamChart', {
        chart: { type: 'column', backgroundColor: 'transparent' },
        title: { text: 'Top Team Members KPI Score' },
        xAxis: { categories: ['L. Mbeki', 'N. Plaatjie', 'S. Dlamini', 'T. Jonas'] },
        yAxis: { title: { text: 'KPI %' }, labels: { format: '{value}%' } },
        series: [{
            name: 'Individual Score',
            data: [147, 112, 105, 89],
            color: brandTeal
        }],
        credits: { enabled: false }
    });
</script>
</body>
</html>
@endsection
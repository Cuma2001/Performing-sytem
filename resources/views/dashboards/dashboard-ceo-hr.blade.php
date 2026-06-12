@extends('layouts.app')

@section('title', 'CEO/HR Dashboard - Performance Management System')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CEO/HR Dashboard - Performance Management System</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
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
            max-width: 1600px;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .stat-card h3 {
            color: var(--primary-teal);
            font-size: 0.85rem;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e2f3f;
        }
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .chart-card h3 {
            color: var(--primary-teal);
            margin-bottom: 16px;
            border-left: 4px solid var(--primary-red);
            padding-left: 12px;
        }
        .chart-container {
            height: 350px;
        }
        @media (max-width: 768px) {
            .chart-grid { grid-template-columns: 1fr; }
            body { padding: 12px; }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div style="background: white; border-radius: 20px; padding: 24px; margin-bottom: 24px; border-left: 6px solid var(--primary-red);">
        <h1 style="color: var(--primary-teal); margin: 0 0 8px 0;">
            <i class="fas fa-chart-line" style="color: var(--primary-red);"></i> 
            Performance Command Center
        </h1>
        <p style="margin: 0; color: #6c757d;">Welcome, <strong>{{ auth()->user()->name }}</strong> • Role: CEO/HR • Full Access</p>
        <p style="margin: 8px 0 0 0; font-size: 0.85rem;">⚡ Weighted Score: 80% Sales + 20% General KPIs | Real-time Data</p>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3><i class="fas fa-users"></i> Total Users</h3>
            <div class="stat-value">{{ $totalUsers }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-store"></i> Total Stores</h3>
            <div class="stat-value">{{ $totalStores }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-chart-line"></i> Active Sales Records</h3>
            <div class="stat-value">{{ number_format($activeSalesRecords) }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-dollar-sign"></i> Total Revenue</h3>
            <div class="stat-value">R {{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> Performance Trends</h3>
            <div id="trendChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-store"></i> Store Performance</h3>
            <div id="storeChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> KPI Distribution</h3>
            <div id="kpiPieChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-simple"></i> Sales Breakdown</h3>
            <div id="salesChart" class="chart-container"></div>
        </div>
    </div>
</div>

<script>
    const brandRed = '#e5222b';
    const brandGold = '#f4c610';
    const brandTeal = '#1d6988';

    // Trend Chart
    Highcharts.chart('trendChart', {
        chart: { type: 'line', backgroundColor: 'transparent' },
        title: { text: 'Quarterly Performance (2025-2026)' },
        xAxis: { categories: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025', 'Q1 2026', 'Q2 2026'] },
        yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' } },
        series: [
            { name: 'Company Avg', data: [78, 82, 85, 88, 91, 94.6], color: brandTeal },
            { name: 'Top Store', data: [82, 86, 90, 94, 98, 104.3], color: brandGold },
            { name: 'Target', data: [80, 80, 85, 85, 90, 95], color: brandRed, dashStyle: 'Dash' }
        ],
        credits: { enabled: false }
    });

    // Store Performance Chart
    Highcharts.chart('storeChart', {
        chart: { type: 'column', backgroundColor: 'transparent' },
        title: { text: 'Store Performance vs Target' },
        xAxis: { categories: ['Hemmingway\'s', 'Beacon Bay', 'Stone Towers', 'Vincent Park'] },
        yAxis: { title: { text: 'Performance (%)' }, labels: { format: '{value}%' } },
        series: [
            { name: 'Actual Performance', data: [104.3, 98.7, 91.2, 87.5], color: brandTeal },
            { name: 'Company Target', data: [94.6, 94.6, 94.6, 94.6], color: brandGold }
        ],
        credits: { enabled: false }
    });

    // KPI Pie Chart
    Highcharts.chart('kpiPieChart', {
        chart: { type: 'pie', backgroundColor: 'transparent', options3d: { enabled: true, alpha: 45 } },
        title: { text: 'KPI Achievement Distribution' },
        series: [{
            name: 'KPIs',
            data: [
                { name: 'Sales Achieved', y: 78, color: brandTeal },
                { name: 'Sales Outstanding', y: 22, color: '#bcbcbc' },
                { name: 'General Achieved', y: 68, color: brandGold },
                { name: 'General Outstanding', y: 32, color: '#e2c28b' }
            ]
        }],
        credits: { enabled: false }
    });

    // Sales Breakdown Chart
    Highcharts.chart('salesChart', {
        chart: { type: 'bar', backgroundColor: 'transparent' },
        title: { text: 'Sales by Category (R Thousands)' },
        xAxis: { categories: ['Accessories', 'Handsets', 'Postpaid', 'Fibre', 'Airtime'] },
        yAxis: { title: { text: 'Amount (R)' }, labels: { format: 'R{value}K' } },
        series: [
            { name: 'Actual', data: [185, 420, 610, 278, 530], color: brandRed },
            { name: 'Target', data: [160, 400, 650, 300, 500], color: brandTeal }
        ],
        credits: { enabled: false }
    });
</script>
</body>
</html>
@endsection
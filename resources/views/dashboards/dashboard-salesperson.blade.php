@extends('layouts.app')

@section('title', 'Salesperson Dashboard - Performance Management System')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Performance Dashboard</title>
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
        .achievement-badge {
            background: linear-gradient(135deg, var(--primary-gold), #f7d44a);
            padding: 8px 16px;
            border-radius: 40px;
            display: inline-block;
            font-weight: bold;
        }
        .recent-sales {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-top: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div style="background: white; border-radius: 20px; padding: 24px; margin-bottom: 24px;">
        <h1 style="color: var(--primary-teal); margin: 0 0 8px 0;">
            <i class="fas fa-user-check" style="color: var(--primary-red);"></i> 
            My Performance Dashboard
        </h1>
        <p>Welcome, <strong>{{ auth()->user()->name }}</strong> • Store: {{ $userStore->name ?? 'N/A' }}</p>
        <div class="achievement-badge">
            <i class="fas fa-star"></i> Top Performer This Month!
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3><i class="fas fa-chart-line"></i> My KPI Score</h3>
            <div class="stat-value" style="color: var(--primary-teal);">108.3%</div>
            <small>⬆️ +12% vs last month</small>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-dollar-sign"></i> My Revenue</h3>
            <div class="stat-value" style="color: var(--primary-gold);">
                R {{ number_format($personalRevenue, 2) }}
            </div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-tasks"></i> Sales Completed</h3>
            <div class="stat-value" style="color: var(--primary-red);">
                {{ $personalSalesRecords }}
            </div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-gift"></i> Incentive Earned</h3>
            <div class="stat-value">R 3,250</div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> My Performance Trend</h3>
            <div id="myTrend" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Sales by Category</h3>
            <div id="myPie" class="chart-container"></div>
        </div>
    </div>

    <div class="recent-sales">
        <h3><i class="fas fa-history"></i> Recent Sales Activity</h3>
        <table>
            <thead>
                <tr><th>Date</th><th>Product</th><th>Amount</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($recentSales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                    <td>{{ $sale->product_name ?? 'Product' }}</td>
                    <td>R {{ number_format($sale->amount, 2) }}</td>
                    <td><span style="color: green;">✓ Completed</span></td>
                </tr>
                @empty
                <tr><td colspan="4">No sales records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const brandRed = '#e5222b';
    const brandTeal = '#1d6988';
    const brandGold = '#f4c610';
    
    Highcharts.chart('myTrend', {
        chart: { type: 'line', backgroundColor: 'transparent' },
        title: { text: 'My Monthly Performance (2026)' },
        xAxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
        yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' } },
        series: [{
            name: 'My KPI',
            data: [88, 94, 102, 108, 112, 115],
            color: brandRed,
            lineWidth: 3
        }, {
            name: 'Store Avg',
            data: [85, 89, 94, 98, 101, 103],
            color: brandTeal
        }],
        credits: { enabled: false }
    });
    
    Highcharts.chart('myPie', {
        chart: { type: 'pie', backgroundColor: 'transparent' },
        title: { text: 'My Sales Contribution' },
        series: [{
            name: 'Categories',
            data: [
                { name: 'Handsets', y: 48, color: brandTeal },
                { name: 'Postpaid', y: 28, color: brandGold },
                { name: 'Accessories', y: 15, color: brandRed },
                { name: 'Fibre', y: 9, color: '#e2c28b' }
            ]
        }],
        credits: { enabled: false }
    });
</script>
</body>
</html>
@endsection
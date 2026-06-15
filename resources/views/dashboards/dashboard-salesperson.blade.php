@extends('layouts.app')

@section('title', 'My Performance Dashboard - Salesperson')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Performance Dashboard</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-red: #e5222b;
            --primary-gold: #f4c610;
            --primary-teal: #1d6988;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
        }

        /* Salesperson Dashboard Layout */
        .sales-dashboard {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header Section */
        .dashboard-header {
            background: white;
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 6px solid var(--primary-red);
        }

        .welcome-badge {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .welcome-badge h1 {
            color: var(--primary-teal);
            font-size: 1.8rem;
            margin-bottom: 8px;
        }

        .rating-badge {
            background: var(--primary-gold);
            padding: 8px 20px;
            border-radius: 40px;
            font-weight: 700;
            color: #1e2f3f;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s;
            border-top: 4px solid var(--primary-gold);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            margin-bottom: 12px;
        }

        .stat-card h3 {
            color: var(--primary-teal);
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1e2f3f;
        }

        .stat-change {
            font-size: 0.75rem;
            margin-top: 8px;
        }

        .trend-up { color: #2b7e3a; }
        .trend-down { color: #e5222b; }

        /* Chart Grid */
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .chart-card h3 {
            color: var(--primary-teal);
            margin-bottom: 16px;
            border-left: 4px solid var(--primary-red);
            padding-left: 12px;
        }

        .chart-container {
            height: 320px;
        }

        /* Achievement Section */
        .achievement-section {
            background: linear-gradient(135deg, var(--primary-teal) 0%, #0e4b64 100%);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            color: white;
        }

        .achievement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 16px;
        }

        .achievement-item {
            text-align: center;
            padding: 16px;
            background: rgba(255,255,255,0.1);
            border-radius: 16px;
        }

        .achievement-item i {
            font-size: 2rem;
            color: var(--primary-gold);
            margin-bottom: 8px;
        }

        .achievement-item h4 {
            font-size: 0.85rem;
            margin-bottom: 4px;
        }

        .achievement-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Recent Activity Table */
        .recent-activity {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .recent-activity h3 {
            color: var(--primary-teal);
            margin-bottom: 16px;
            border-left: 4px solid var(--primary-gold);
            padding-left: 12px;
        }

        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .activity-table th,
        .activity-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .activity-table th {
            background: #f8fafc;
            color: var(--primary-teal);
            font-weight: 600;
        }

        .status-completed {
            color: #2b7e3a;
            font-weight: 600;
        }

        .status-pending {
            color: #f4c610;
            font-weight: 600;
        }

        /* Leaderboard */
        .leaderboard {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .leaderboard h3 {
            color: var(--primary-teal);
            margin-bottom: 16px;
            border-left: 4px solid var(--primary-red);
            padding-left: 12px;
        }

        .leaderboard-list {
            list-style: none;
        }

        .leaderboard-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .rank {
            font-weight: 700;
            width: 40px;
        }

        .rank-1 { color: #f4c610; }
        .rank-2 { color: #c0c0c0; }
        .rank-3 { color: #cd7f32; }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 0 12px;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary-teal);
            border-radius: 10px;
            transition: width 0.5s;
        }

        @media (max-width: 768px) {
            .sales-dashboard { padding: 16px; }
            .chart-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
<div class="sales-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="welcome-badge">
            <div>
                <h1><i class="fas fa-user-check" style="color: var(--primary-red);"></i> My Performance Dashboard</h1>
                <p style="color: #6c757d; margin-top: 8px;">
                    Welcome back, <strong>{{ auth()->user()->name }}</strong> • 
                    Store: <strong>{{ $userStore->name ?? 'Hemmingway\'s' }}</strong> • 
                    Role: Salesperson/CSR
                </p>
            </div>
            <div class="rating-badge">
                <i class="fas fa-star"></i> Rating: Excellent
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-line" style="color: var(--primary-teal);"></i></div>
            <h3>My Overall KPI Score</h3>
            <div class="stat-value">108.3%</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> +12% vs last month</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-dollar-sign" style="color: var(--primary-gold);"></i></div>
            <h3>Revenue Generated</h3>
            <div class="stat-value">R {{ number_format($personalRevenue ?? 247500, 2) }}</div>
            <div class="stat-change trend-up"><i class="fas fa-arrow-up"></i> 18% above target</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-tasks" style="color: var(--primary-red);"></i></div>
            <h3>Sales Completed</h3>
            <div class="stat-value">{{ $personalSalesRecords ?? 156 }}</div>
            <div class="stat-change">This month: 24 transactions</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-gift" style="color: var(--primary-teal);"></i></div>
            <h3>Incentive Earned</h3>
            <div class="stat-value">R {{ number_format($incentive ?? 3250, 2) }}</div>
            <div class="stat-change">Bonus pending: R1,500</div>
        </div>
    </div>

    <!-- Achievement Section -->
    <div class="achievement-section">
        <h3><i class="fas fa-trophy"></i> My Achievements</h3>
        <div class="achievement-grid">
            <div class="achievement-item">
                <i class="fas fa-crown"></i>
                <h4>Store Rank</h4>
                <div class="achievement-value">#1</div>
                <small>Top Performer</small>
            </div>
            <div class="achievement-item">
                <i class="fas fa-chart-line"></i>
                <h4>Target Achievement</h4>
                <div class="achievement-value">147%</div>
                <small>Exceeded by 47%</small>
            </div>
            <div class="achievement-item">
                <i class="fas fa-smile"></i>
                <h4>Customer Satisfaction</h4>
                <div class="achievement-value">4.9/5</div>
                <small>98% positive</small>
            </div>
            <div class="achievement-item">
                <i class="fas fa-calendar-check"></i>
                <h4>Attendance</h4>
                <div class="achievement-value">100%</div>
                <small>Perfect attendance</small>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-line"></i> My Performance Trend (2026)</h3>
            <div id="myTrendChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Sales by Category</h3>
            <div id="salesCategoryChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-simple"></i> Target vs Actual by Product</h3>
            <div id="targetVsActualChart" class="chart-container"></div>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Weekly Performance</h3>
            <div id="weeklyChart" class="chart-container"></div>
        </div>
    </div>

    <!-- Recent Activity & Leaderboard -->
    <div class="chart-grid">
        <div class="recent-activity">
            <h3><i class="fas fa-history"></i> Recent Sales Activity</h3>
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product/Service</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales ?? [] as $sale)
                    <tr>
                        <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                        <td>{{ $sale->product_name ?? 'Product Sale' }}</td>
                        <td>R {{ number_format($sale->amount ?? 0, 2) }}</td>
                        <td class="status-completed"><i class="fas fa-check-circle"></i> Completed</td>
                    </tr>
                    @empty
                    <tr>
                        <td>2026-06-10</td>
                        <td>iPhone 15 Pro</td>
                        <td>R 21,999</td>
                        <td class="status-completed">✓ Completed</td>
                    </tr>
                    <tr>
                        <td>2026-06-09</td>
                        <td>Postpaid Contract (24mo)</td>
                        <td>R 899</td>
                        <td class="status-completed">✓ Completed</td>
                    </tr>
                    <tr>
                        <td>2026-06-08</td>
                        <td>Fibre Installation</td>
                        <td>R 1,299</td>
                        <td class="status-completed">✓ Completed</td>
                    </tr>
                    <tr>
                        <td>2026-06-07</td>
                        <td>Accessories Bundle</td>
                        <td>R 1,450</td>
                        <td class="status-completed">✓ Completed</td>
                    </tr>
                    <tr>
                        <td>2026-06-06</td>
                        <td>Samsung Galaxy S24</td>
                        <td>R 18,999</td>
                        <td class="status-completed">✓ Completed</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="leaderboard">
            <h3><i class="fas fa-medal"></i> Store Leaderboard</h3>
            <ul class="leaderboard-list">
                <li>
                    <span class="rank rank-1"><i class="fas fa-crown"></i> #1</span>
                    <span><strong>Me</strong> (Lerato Mbeki)</span>
                    <span><strong>108.3%</strong></span>
                </li>
                <li>
                    <span class="rank rank-2">#2</span>
                    <span>N. Plaatjie</span>
                    <span>98.7%</span>
                </li>
                <li>
                    <span class="rank rank-3">#3</span>
                    <span>S. Dlamini</span>
                    <span>92.4%</span>
                </li>
                <li>
                    <span class="rank">#4</span>
                    <span>T. Jonas</span>
                    <span>85.2%</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 85%"></div>
                    </div>
                </li>
                <li>
                    <span class="rank">#5</span>
                    <span>M. Nkosi</span>
                    <span>78.9%</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 79%"></div>
                    </div>
                </li>
            </ul>
            
            <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                <h4 style="color: var(--primary-teal); margin-bottom: 12px;">
                    <i class="fas fa-chart-line"></i> My Target Progress
                </h4>
                <div style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Monthly Target: R210,000</span>
                        <span><strong>R247,500 / R210,000</strong></span>
                    </div>
                    <div class="progress-bar" style="width: 100%;">
                        <div class="progress-fill" style="width: 118%; background: var(--primary-red);"></div>
                    </div>
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Quarterly Target: R630,000</span>
                        <span><strong>R685,000 / R630,000</strong></span>
                    </div>
                    <div class="progress-bar" style="width: 100%;">
                        <div class="progress-fill" style="width: 109%; background: var(--primary-gold);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Targets Card -->
    <div class="chart-card" style="margin-top: 24px;">
        <h3><i class="fas fa-bullseye"></i> My Next Targets & Goals</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-top: 16px;">
            <div style="padding: 16px; background: #f8fafc; border-radius: 16px;">
                <i class="fas fa-chart-line" style="color: var(--primary-teal);"></i>
                <strong>Fibre Sales Target</strong>
                <p style="margin-top: 8px;">Current: 8 connections | Target: 12 connections</p>
                <div class="progress-bar" style="width: 100%; margin-top: 8px;">
                    <div class="progress-fill" style="width: 67%; background: var(--primary-teal);"></div>
                </div>
            </div>
            <div style="padding: 16px; background: #f8fafc; border-radius: 16px;">
                <i class="fas fa-chart-line" style="color: var(--primary-gold);"></i>
                <strong>Retention KPI</strong>
                <p style="margin-top: 8px;">Current: 92% | Target: 95%</p>
                <div class="progress-bar" style="width: 100%; margin-top: 8px;">
                    <div class="progress-fill" style="width: 97%; background: var(--primary-gold);"></div>
                </div>
            </div>
            <div style="padding: 16px; background: #f8fafc; border-radius: 16px;">
                <i class="fas fa-chart-line" style="color: var(--primary-red);"></i>
                <strong>Customer Experience Score</strong>
                <p style="margin-top: 8px;">Current: 4.9 | Target: 4.8 ✓ Achieved</p>
                <div class="progress-bar" style="width: 100%; margin-top: 8px;">
                    <div class="progress-fill" style="width: 100%; background: var(--primary-red);"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart 1: My Performance Trend
    Highcharts.chart('myTrendChart', {
        chart: { type: 'line', backgroundColor: 'transparent' },
        title: { text: undefined },
        xAxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
        yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' }, min: 60 },
        series: [
            { name: 'My KPI', data: [78, 84, 92, 102, 108, 112], color: '#e5222b', lineWidth: 3 },
            { name: 'Store Average', data: [75, 80, 86, 92, 96, 101], color: '#1d6988' },
            { name: 'Company Target', data: [80, 80, 85, 85, 90, 95], color: '#f4c610', dashStyle: 'Dash' }
        ],
        credits: { enabled: false }
    });

    // Chart 2: Sales by Category
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

    // Chart 3: Target vs Actual by Product
    Highcharts.chart('targetVsActualChart', {
        chart: { type: 'column', backgroundColor: 'transparent' },
        title: { text: undefined },
        xAxis: { categories: ['Handsets', 'Postpaid', 'Accessories', 'Fibre', 'Airtime'] },
        yAxis: { title: { text: 'Amount (R Thousands)' } },
        series: [
            { name: 'Actual', data: [78, 45, 32, 12, 8], color: '#e5222b' },
            { name: 'Target', data: [65, 38, 24, 10, 6], color: '#1d6988' }
        ],
        credits: { enabled: false }
    });

    // Chart 4: Weekly Performance
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

    // Auto-refresh data every 30 seconds (optional)
    setInterval(function() {
        // Refresh dashboard data via AJAX
        $.get(window.location.href, function(data) {
            // Update stats dynamically
            console.log('Dashboard refreshed');
        });
    }, 30000);
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
@endsection
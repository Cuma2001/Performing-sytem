@extends('layouts.app')

@section('title', 'My Performance Dashboard - Salesperson')

@section('content')
<div class="sales-dashboard" style="max-width: 1400px; margin: 0 auto; padding: 0;">
    <div class="page-hero" style="margin-bottom: 24px;">
        <div>
            <div class="page-hero__eyebrow"><i class="fas fa-user-check"></i> Employee view</div>
            <h2 class="page-hero__title">My performance dashboard</h2>
            <p class="page-hero__text">This view gives your team a clear snapshot of achievements, targets, and recent activity, similar to the executive dashboard experience.</p>
        </div>
        <div class="page-hero__meta">
            <div class="info-pill"><i class="fas fa-store"></i> {{ $userStore->name ?? "Hemmingway's" }}</div>
            <div class="info-pill"><i class="fas fa-star"></i> Excellent rating</div>
        </div>
    </div>

    <div class="dashboard-header" style="background: white; border-radius: 20px; padding: 24px; margin-bottom: 24px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06); border-left: 6px solid var(--primary-red);">
        <div class="welcome-badge" style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <h1 style="color: var(--primary-teal); font-size: 1.7rem; margin-bottom: 8px;"><i class="fas fa-user-check" style="color: var(--primary-red);"></i> Welcome back, {{ auth()->user()->name ?? 'Employee' }}</h1>
                <p style="color: #6c757d; margin-top: 8px;">
                    Store: <strong>{{ $userStore->name ?? "Hemmingway's" }}</strong> • Role: Salesperson/CSR • Activity: <strong>24 transactions this month</strong>
                </p>
            </div>
            <div class="rating-badge" style="background: var(--primary-gold); padding: 8px 18px; border-radius: 999px; font-weight: 700; color: #1e2f3f;">
                <i class="fas fa-star"></i> Rating: Excellent
            </div>
        </div>
    </div>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 24px;">
        <div class="stat-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06); border-top: 4px solid var(--primary-gold);">
            <div class="stat-icon"><i class="fas fa-chart-line" style="color: var(--primary-teal);"></i></div>
            <h3 style="color: var(--primary-teal); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">My overall KPI score</h3>
            <div class="stat-value" style="font-size: 2rem; font-weight: 800; color: #1e2f3f;">108.3%</div>
            <div class="stat-change trend-up" style="font-size: 0.75rem; margin-top: 8px;"><i class="fas fa-arrow-up"></i> +12% vs last month</div>
        </div>
        <div class="stat-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06); border-top: 4px solid var(--primary-gold);">
            <div class="stat-icon"><i class="fas fa-dollar-sign" style="color: var(--primary-gold);"></i></div>
            <h3 style="color: var(--primary-teal); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Revenue generated</h3>
            <div class="stat-value" style="font-size: 2rem; font-weight: 800; color: #1e2f3f;">R {{ number_format($personalRevenue ?? 247500, 2) }}</div>
            <div class="stat-change trend-up" style="font-size: 0.75rem; margin-top: 8px;"><i class="fas fa-arrow-up"></i> 18% above target</div>
        </div>
        <div class="stat-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06); border-top: 4px solid var(--primary-gold);">
            <div class="stat-icon"><i class="fas fa-tasks" style="color: var(--primary-red);"></i></div>
            <h3 style="color: var(--primary-teal); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Sales completed</h3>
            <div class="stat-value" style="font-size: 2rem; font-weight: 800; color: #1e2f3f;">{{ $personalSalesRecords ?? 156 }}</div>
            <div class="stat-change" style="font-size: 0.75rem; margin-top: 8px;">This month: 24 transactions</div>
        </div>
        <div class="stat-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06); border-top: 4px solid var(--primary-gold);">
            <div class="stat-icon"><i class="fas fa-gift" style="color: var(--primary-teal);"></i></div>
            <h3 style="color: var(--primary-teal); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 8px;">Incentive earned</h3>
            <div class="stat-value" style="font-size: 2rem; font-weight: 800; color: #1e2f3f;">R {{ number_format(3250, 2) }}</div>
            <div class="stat-change" style="font-size: 0.75rem; margin-top: 8px;">Bonus pending: R1,500</div>
        </div>
    </div>

    <div class="achievement-section" style="background: linear-gradient(135deg, var(--primary-teal) 0%, #0e4b64 100%); border-radius: 20px; padding: 24px; margin-bottom: 24px; color: white;">
        <h3><i class="fas fa-trophy"></i> My achievements</h3>
        <div class="achievement-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 16px;">
            <div class="achievement-item" style="text-align: center; padding: 16px; background: rgba(255,255,255,0.12); border-radius: 16px;">
                <i class="fas fa-crown"></i>
                <h4 style="font-size: 0.85rem; margin: 6px 0 4px;">Store rank</h4>
                <div class="achievement-value" style="font-size: 1.4rem; font-weight: 700;">#1</div>
                <small>Top performer</small>
            </div>
            <div class="achievement-item" style="text-align: center; padding: 16px; background: rgba(255,255,255,0.12); border-radius: 16px;">
                <i class="fas fa-chart-line"></i>
                <h4 style="font-size: 0.85rem; margin: 6px 0 4px;">Target achievement</h4>
                <div class="achievement-value" style="font-size: 1.4rem; font-weight: 700;">147%</div>
                <small>Exceeded by 47%</small>
            </div>
            <div class="achievement-item" style="text-align: center; padding: 16px; background: rgba(255,255,255,0.12); border-radius: 16px;">
                <i class="fas fa-smile"></i>
                <h4 style="font-size: 0.85rem; margin: 6px 0 4px;">Customer satisfaction</h4>
                <div class="achievement-value" style="font-size: 1.4rem; font-weight: 700;">4.9/5</div>
                <small>98% positive</small>
            </div>
            <div class="achievement-item" style="text-align: center; padding: 16px; background: rgba(255,255,255,0.12); border-radius: 16px;">
                <i class="fas fa-calendar-check"></i>
                <h4 style="font-size: 0.85rem; margin: 6px 0 4px;">Attendance</h4>
                <div class="achievement-value" style="font-size: 1.4rem; font-weight: 700;">100%</div>
                <small>Perfect attendance</small>
            </div>
        </div>
    </div>

    <div class="chart-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px; margin-bottom: 24px;">
        <div class="chart-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06);">
            <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-red); padding-left: 12px;"><i class="fas fa-chart-line"></i> My performance trend</h3>
            <div id="myTrendChart" style="height: 320px;"></div>
        </div>
        <div class="chart-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06);">
            <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-red); padding-left: 12px;"><i class="fas fa-chart-pie"></i> Sales by category</h3>
            <div id="salesCategoryChart" style="height: 320px;"></div>
        </div>
        <div class="chart-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06);">
            <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-red); padding-left: 12px;"><i class="fas fa-balance-scale"></i> Target vs actual</h3>
            <div id="targetVsActualChart" style="height: 320px;"></div>
        </div>
        <div class="chart-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06);">
            <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-red); padding-left: 12px;"><i class="fas fa-chart-bar"></i> Weekly performance</h3>
            <div id="weeklyChart" style="height: 320px;"></div>
        </div>
    </div>

    <div class="chart-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px; margin-bottom: 24px;">
        <div class="chart-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06);">
            <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-gold); padding-left: 12px;"><i class="fas fa-history"></i> Recent activity</h3>
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Date</th>
                            <th style="padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Item</th>
                            <th style="padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Amount</th>
                            <th style="padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales ?? [] as $sale)
                        <tr>
                            <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">{{ $sale->created_at->format('Y-m-d') }}</td>
                            <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">{{ $sale->product_name ?? 'Product Sale' }}</td>
                            <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">R {{ number_format($sale->amount ?? 0, 2) }}</td>
                            <td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #2b7e3a; font-weight: 600;"><i class="fas fa-check-circle"></i> Completed</td>
                        </tr>
                        @empty
                        <tr><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">2026-06-10</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">iPhone 15 Pro</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">R 21,999</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #2b7e3a; font-weight: 600;">✓ Completed</td></tr>
                        <tr><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">2026-06-09</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">Postpaid Contract</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">R 899</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #2b7e3a; font-weight: 600;">✓ Completed</td></tr>
                        <tr><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">2026-06-08</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">Fibre Installation</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0;">R 1,299</td><td style="padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #2b7e3a; font-weight: 600;">✓ Completed</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="chart-card" style="background: white; border-radius: 18px; padding: 20px; box-shadow: 0 10px 24px rgba(15, 30, 44, 0.06);">
            <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-red); padding-left: 12px;"><i class="fas fa-medal"></i> Team leaderboard</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 700; color: #f4c610;">#1</span><span><strong>Me</strong></span><span>108.3%</span></li>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 700; color: #c0c0c0;">#2</span><span>N. Plaatjie</span><span>98.7%</span></li>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 700; color: #cd7f32;">#3</span><span>S. Dlamini</span><span>92.4%</span></li>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #e2e8f0;"><span>#4</span><span>T. Jonas</span><span>85.2%</span></li>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0;"><span>#5</span><span>M. Nkosi</span><span>78.9%</span></li>
            </ul>

            <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                <h4 style="color: var(--primary-teal); margin-bottom: 12px;"><i class="fas fa-bullseye"></i> Next targets</h4>
                <div style="padding: 12px; background: #f8fafc; border-radius: 12px; margin-bottom: 10px;">
                    <strong>Fibre sales</strong>
                    <div style="font-size: 0.9rem; color: #5b6b79; margin-top: 4px;">Current: 8 | Target: 12</div>
                </div>
                <div style="padding: 12px; background: #f8fafc; border-radius: 12px; margin-bottom: 10px;">
                    <strong>Retention KPI</strong>
                    <div style="font-size: 0.9rem; color: #5b6b79; margin-top: 4px;">Current: 92% | Target: 95%</div>
                </div>
                <div style="padding: 12px; background: #f8fafc; border-radius: 12px;">
                    <strong>Customer experience</strong>
                    <div style="font-size: 0.9rem; color: #5b6b79; margin-top: 4px;">Current: 4.9/5 | Target: 4.8</div>
                </div>
            </div>
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
        yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' }, min: 60 },
        series: [
            { name: 'My KPI', data: [78, 84, 92, 102, 108, 112], color: '#e5222b', lineWidth: 3 },
            { name: 'Store Average', data: [75, 80, 86, 92, 96, 101], color: '#1d6988' },
            { name: 'Company Target', data: [80, 80, 85, 85, 90, 95], color: '#f4c610', dashStyle: 'Dash' }
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
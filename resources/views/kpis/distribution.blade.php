@extends('layouts.app')
@section('content')
    <div style="container-fluid px-4">
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-header">
                KPI Distribution
            </div>

            <div class="card-body">
                <div class="chart-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 24px;">
                    <div class="chart-card" style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-red); padding-left: 12px;">
                            <i class="fas fa-building"></i> Company Level KPIs (SMS Mobile)
                        </h3>
                        <div id="companyKPIPie" class="chart-container" style="height: 350px;"></div>
                    </div>

                    <div class="chart-card" style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-gold); padding-left: 12px;">
                            <i class="fas fa-chart-bar"></i> KPI Weightage Distribution
                        </h3>
                        <div id="weightageChart" class="chart-container" style="height: 350px;"></div>
                    </div>
                </div>

                <div class="chart-card" style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 24px;">
                    <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-teal); padding-left: 12px;">
                        <i class="fas fa-chart-line"></i> Supervisor Performance Distribution
                    </h3>
                    <div id="supervisorKPIBar" class="chart-container" style="height: 350px;"></div>
                </div>

                <div class="chart-card" style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 24px;">
                    <h3 style="color: var(--primary-teal); margin-bottom: 16px; border-left: 4px solid var(--primary-gold); padding-left: 12px;">
                        <i class="fas fa-chart-simple"></i> KPI Breakdown by Category
                    </h3>
                    <div id="kpiBreakdownChart" class="chart-container" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('companyKPIPie', {
            chart: { type: 'pie', backgroundColor: 'transparent', options3d: { enabled: true, alpha: 45 } },
            title: { text: 'Company KPI Distribution' },
            series: [{
                name: 'KPIs',
                data: [
                    { name: 'Sales KPIs', y: 80, color: '#1d6988' },
                    { name: 'General KPIs', y: 20, color: '#f4c610' }
                ]
            }],
            credits: { enabled: false }
        });

        Highcharts.chart('weightageChart', {
            chart: { type: 'bar', backgroundColor: 'transparent' },
            title: { text: 'KPI Weightage by Level' },
            xAxis: { categories: ['Company', 'Store', 'Supervisor', 'Individual'] },
            yAxis: { title: { text: 'Weightage (%)' } },
            series: [{ name: 'Weight', data: [100, 85, 70, 50], color: '#e5222b' }],
            credits: { enabled: false }
        });

        Highcharts.chart('supervisorKPIBar', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: 'Supervisor Performance Distribution' },
            xAxis: { categories: ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Williams'] },
            yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' } },
            series: [{ name: 'Supervisor KPI', data: [92, 88, 95, 84], color: '#1d6988' }],
            credits: { enabled: false }
        });

        Highcharts.chart('kpiBreakdownChart', {
            chart: { type: 'bar', backgroundColor: 'transparent' },
            title: { text: 'KPI Breakdown by Category' },
            xAxis: { categories: ['Sales', 'Customer Exp', 'Stock', 'Compliance', 'HR'] },
            yAxis: { title: { text: 'Achievement (%)' }, labels: { format: '{value}%' } },
            series: [
                { name: 'Target', data: [100, 90, 85, 95, 80], color: '#f4c610' },
                { name: 'Actual', data: [97, 88, 82, 91, 78], color: '#1d6988' }
            ],
            credits: { enabled: false }
        });
    </script>
@endsection
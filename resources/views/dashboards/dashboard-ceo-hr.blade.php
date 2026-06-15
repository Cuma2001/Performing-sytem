@extends('layouts.app')

@section('title', 'CEO/HR Dashboard - Performance Management System')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CEO/HR Dashboard</title>
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
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1a2c3e 0%, #0f1e2c 100%);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 24px;
        }

        .sidebar-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-gold);
        }

        .sidebar-header p {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.6);
            margin-top: 4px;
        }

        .nav-menu {
            list-style: none;
            padding: 0 16px;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .nav-link.active {
            background: var(--primary-red);
            color: white;
        }

        .nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 24px 32px;
            transition: all 0.3s ease;
        }

        /* Page Content */
        .page-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .page-content.active-page {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary-gold);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
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

        /* Chart Grid */
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

        /* Upload Forms */
        .upload-section {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .upload-section h3 {
            color: var(--primary-teal);
            margin-bottom: 16px;
            border-left: 4px solid var(--primary-gold);
            padding-left: 12px;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .upload-card {
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        .upload-card:hover {
            border-color: var(--primary-teal);
            background: #f8fafc;
        }

        .upload-card i {
            font-size: 48px;
            color: var(--primary-teal);
            margin-bottom: 12px;
        }

        .upload-card h4 {
            color: #1e2f3f;
            margin-bottom: 8px;
        }

        .upload-card input[type="file"] {
            display: none;
        }

        .upload-btn {
            background: var(--primary-teal);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 12px;
            font-size: 14px;
        }

        .upload-btn:hover {
            background: #0e5a75;
        }

        /* Comparison Table */
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .comparison-table th,
        .comparison-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .comparison-table th {
            background: #f8fafc;
            color: var(--primary-teal);
            font-weight: 600;
        }

        .kpi-highlight {
            background: var(--primary-gold);
            color: #1e2f3f;
            padding: 4px 8px;
            border-radius: 20px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed);
            }
            .sidebar-header h2, .sidebar-header p, .nav-link span {
                display: none;
            }
            .main-content {
                margin-left: var(--sidebar-collapsed);
            }
            .chart-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="public/assets/images/Screenshot_2026-02-10_085819-removebg-preview.png" alt="Logo" style="width: 40px; margin-bottom: 8px;">
            <p>Performance Management</p>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <div class="nav-link active" data-page="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </div>
            </li>
            <li class="nav-item">
                <div class="nav-link" data-page="kpi-distribution">
                    <i class="fas fa-chart-pie"></i>
                    <span>KPI Distribution</span>
                </div>
            </li>
            <li class="nav-item">
                <div class="nav-link" data-page="kpi-upload">
                    <i class="fas fa-upload"></i>
                    <span>KPI Upload</span>
                </div>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page 1: Dashboard -->
        <div id="dashboard-page" class="page-content active-page">
            <div style="margin-bottom: 24px;">
                <h1 style="color: var(--primary-teal);">Performance Dashboard</h1>
                <p>Welcome back, <strong>{{ auth()->user()->name }}</strong> | Real-time KPI Tracking</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><i class="fas fa-store"></i> Total Stores</h3>
                    <div class="stat-value">{{ $totalStores ?? 5 }}</div>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-users"></i> Total Users</h3>
                    <div class="stat-value">{{ $totalUsers ?? 25 }}</div>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-chart-line"></i> Company KPI Score</h3>
                    <div class="stat-value">94.6%</div>
                </div>
                <div class="stat-card">
                    <h3><i class="fas fa-dollar-sign"></i> Total Revenue</h3>
                    <div class="stat-value">R {{ number_format($totalRevenue ?? 0, 2) }}</div>
                </div>
            </div>

            <!-- SMS Mobile vs MTN KPI Comparison Chart -->
            <div class="chart-card" style="margin-bottom: 24px;">
                <h3><i class="fas fa-chart-bar"></i> SMS Mobile vs MTN KPI Comparison</h3>
                <div id="comparisonChart" class="chart-container"></div>
            </div>

            <!-- Other Charts -->
            <div class="chart-grid">
                <div class="chart-card">
                    <h3><i class="fas fa-chart-line"></i> Company Performance Trend</h3>
                    <div id="trendChart" class="chart-container"></div>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-store"></i> Store Performance Ranking</h3>
                    <div id="storeChart" class="chart-container"></div>
                </div>
            </div>

            <!-- KPI Summary Table -->
            <div class="chart-card">
                <h3><i class="fas fa-table"></i> KPI Summary by Store</h3>
                <table class="comparison-table" id="kpiSummaryTable">
                    <thead>
                        <tr><th>Store Name</th><th>Sales KPI</th><th>General KPI</th><th>Overall Score</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Hemmingway's</td><td>98%</td><td>92%</td><td>104.3%</td><td style="color: green;">✓ Exceeded</td></tr>
                        <tr><td>Beacon Bay</td><td>95%</td><td>88%</td><td>98.7%</td><td style="color: green;">✓ Met</td></tr>
                        <tr><td>Stone Towers</td><td>87%</td><td>79%</td><td>91.2%</td><td style="color: orange;">⚠ Below Target</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Page 2: KPI Distribution -->
        <div id="kpi-distribution-page" class="page-content">
            <div style="margin-bottom: 24px;">
                <h1 style="color: var(--primary-teal);">KPI Distribution</h1>
                <p>View KPI targets across all levels</p>
            </div>

            <div class="chart-grid">
                <div class="chart-card">
                    <h3><i class="fas fa-building"></i> Company Level KPIs (SMS Mobile)</h3>
                    <div id="companyKPIPie" class="chart-container"></div>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-chart-bar"></i> KPI Weightage Distribution</h3>
                    <div id="weightageChart" class="chart-container"></div>
                </div>
            </div>

            <div class="chart-card">
                <h3><i class="fas fa-chart-line"></i> Supervisor Performance Distribution</h3>
                <div id="supervisorKPIBar" class="chart-container"></div>
            </div>

            <div class="chart-card">
                <h3><i class="fas fa-chart-simple"></i> KPI Breakdown by Category</h3>
                <div id="kpiBreakdownChart" class="chart-container"></div>
            </div>
        </div>

        <!-- Page 3: KPI Upload -->
        <div id="kpi-upload-page" class="page-content">
            <div style="margin-bottom: 24px;">
                <h1 style="color: var(--primary-teal);">KPI Upload Utility</h1>
                <p>Upload KPI spreadsheets for stores, supervisors, and company-wide targets</p>
            </div>

            <!-- Upload Forms -->
            <div class="upload-section">
                <h3><i class="fas fa-cloud-upload-alt"></i> Upload KPI Data</h3>
                <div class="upload-grid">
                    <!-- Store KPI Upload -->
                    <div class="upload-card">
                        <i class="fas fa-store"></i>
                        <h4>Store KPI Targets</h4>
                        <p>Upload Excel/CSV with store-level KPI targets</p>
                        <form id="storeKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="storeFile" accept=".xlsx,.csv,.xls">
                            <button type="button" class="upload-btn" onclick="document.getElementById('storeFile').click()">
                                <i class="fas fa-folder-open"></i> Choose File
                            </button>
                            <button type="submit" class="upload-btn" style="background: var(--primary-red);">
                                <i class="fas fa-upload"></i> Upload Store KPIs
                            </button>
                        </form>
                        <div id="storeUploadStatus"></div>
                    </div>

                    <!-- Supervisor KPI Upload -->
                    <div class="upload-card">
                        <i class="fas fa-user-tie"></i>
                        <h4>Supervisor KPI Targets</h4>
                        <p>Upload Excel/CSV with supervisor-level KPIs</p>
                        <form id="supervisorKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="supervisorFile" accept=".xlsx,.csv,.xls">
                            <button type="button" class="upload-btn" onclick="document.getElementById('supervisorFile').click()">
                                <i class="fas fa-folder-open"></i> Choose File
                            </button>
                            <button type="submit" class="upload-btn" style="background: var(--primary-red);">
                                <i class="fas fa-upload"></i> Upload Supervisor KPIs
                            </button>
                        </form>
                        <div id="supervisorUploadStatus"></div>
                    </div>

                    <!-- SMS Mobile Company KPI Upload -->
                    <div class="upload-card">
                        <i class="fas fa-chart-line"></i>
                        <h4>SMS Mobile Company KPIs</h4>
                        <p>Upload company-wide SMS Mobile KPI targets</p>
                        <form id="companyKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="companyFile" accept=".xlsx,.csv,.xls">
                            <button type="button" class="upload-btn" onclick="document.getElementById('companyFile').click()">
                                <i class="fas fa-folder-open"></i> Choose File
                            </button>
                            <button type="submit" class="upload-btn" style="background: var(--primary-red);">
                                <i class="fas fa-upload"></i> Upload Company KPIs
                            </button>
                        </form>
                        <div id="companyUploadStatus"></div>
                    </div>

                    <!-- MTN KPI Upload -->
                    <div class="upload-card">
                        <i class="fas fa-chart-line"></i>
                        <h4>MTN Benchmark KPIs</h4>
                        <p>Upload MTN KPI targets for comparison</p>
                        <form id="mtnKPIForm" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" id="mtnFile" accept=".xlsx,.csv,.xls">
                            <button type="button" class="upload-btn" onclick="document.getElementById('mtnFile').click()">
                                <i class="fas fa-folder-open"></i> Choose File
                            </button>
                            <button type="submit" class="upload-btn" style="background: var(--primary-red);">
                                <i class="fas fa-upload"></i> Upload MTN KPIs
                            </button>
                        </form>
                        <div id="mtnUploadStatus"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Uploads History -->
            <div class="chart-card">
                <h3><i class="fas fa-history"></i> Recent Upload History</h3>
                <table class="comparison-table">
                    <thead>
                        <tr><th>Date</th><th>File Name</th><th>Type</th><th>Status</th><th>Records</th></tr>
                    </thead>
                    <tbody id="uploadHistoryTable">
                        <tr><td colspan="5" style="text-align: center;">No uploads yet</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Page Navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const pageId = this.getAttribute('data-page');
                
                // Update active state
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Show selected page
                document.querySelectorAll('.page-content').forEach(page => page.classList.remove('active-page'));
                document.getElementById(`${pageId}-page`).classList.add('active-page');
            });
        });

        // Chart: SMS Mobile vs MTN KPI Comparison
        Highcharts.chart('comparisonChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: 'SMS Mobile vs MTN KPI Benchmark' },
            xAxis: { categories: ['Sales KPI', 'Customer Experience', 'Retention Rate', 'Fibre Sales', 'Overall Score'] },
            yAxis: { title: { text: 'Score (%)' }, labels: { format: '{value}%' } },
            series: [
                { name: 'SMS Mobile', data: [94, 88, 76, 82, 94.6], color: '#1d6988' },
                { name: 'MTN Benchmark', data: [89, 85, 72, 78, 89.2], color: '#f4c610' }
            ],
            credits: { enabled: false }
        });

        // Trend Chart
        Highcharts.chart('trendChart', {
            chart: { type: 'line', backgroundColor: 'transparent' },
            title: { text: 'Quarterly Performance Trend' },
            xAxis: { categories: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025', 'Q1 2026', 'Q2 2026'] },
            yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' } },
            series: [
                { name: 'Company Avg', data: [78, 82, 85, 88, 91, 94.6], color: '#1d6988' },
                { name: 'Top Store', data: [82, 86, 90, 94, 98, 104.3], color: '#f4c610' }
            ],
            credits: { enabled: false }
        });

        // Store Performance Chart
        Highcharts.chart('storeChart', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: 'Store Performance Ranking' },
            xAxis: { categories: ['Hemmingway\'s', 'Beacon Bay', 'Stone Towers', 'Vincent Park'] },
            yAxis: { title: { text: 'Overall Score (%)' }, labels: { format: '{value}%' } },
            series: [{ name: 'Store Score', data: [104.3, 98.7, 91.2, 87.5], color: '#1d6988' }],
            credits: { enabled: false }
        });

        // KPI Distribution Pie Chart
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

        // Weightage Chart
        Highcharts.chart('weightageChart', {
            chart: { type: 'bar', backgroundColor: 'transparent' },
            title: { text: 'KPI Weightage by Level' },
            xAxis: { categories: ['Company', 'Store', 'Supervisor', 'Individual'] },
            yAxis: { title: { text: 'Weightage (%)' } },
            series: [{ name: 'Weight', data: [100, 85, 70, 50], color: '#e5222b' }],
            credits: { enabled: false }
        });

        // Supervisor KPI Bar Chart
        Highcharts.chart('supervisorKPIBar', {
            chart: { type: 'column', backgroundColor: 'transparent' },
            title: { text: 'Supervisor Performance Distribution' },
            xAxis: { categories: ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Williams'] },
            yAxis: { title: { text: 'KPI Score (%)' }, labels: { format: '{value}%' } },
            series: [{ name: 'Supervisor KPI', data: [92, 88, 95, 84], color: '#1d6988' }],
            credits: { enabled: false }
        });

        // KPI Breakdown Chart
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

        // File Upload Handlers with AJAX
        function setupUpload(formId, fileInputId, uploadType, statusDivId) {
            $(`#${formId}`).on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('upload_type', uploadType);
                
                $(`#${statusDivId}`).html('<p style="color: #1d6988;">Uploading...</p>');
                
                $.ajax({
                    url: '{{ route("utility.master-process") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        $(`#${statusDivId}`).html('<p style="color: green;">✓ Upload successful! ' + (response.records || 0) + ' records processed.</p>');
                        loadUploadHistory();
                        setTimeout(() => location.reload(), 2000);
                    },
                    error: function(xhr) {
                        $(`#${statusDivId}`).html('<p style="color: red;">✗ Upload failed: ' + (xhr.responseJSON?.message || 'Unknown error') + '</p>');
                    }
                });
            });
        }

        // Initialize upload handlers
        setupUpload('storeKPIForm', 'storeFile', 'store', 'storeUploadStatus');
        setupUpload('supervisorKPIForm', 'supervisorFile', 'supervisor', 'supervisorUploadStatus');
        setupUpload('companyKPIForm', 'companyFile', 'company', 'companyUploadStatus');
        setupUpload('mtnKPIForm', 'mtnFile', 'mtn', 'mtnUploadStatus');

        // Load upload history
        function loadUploadHistory() {
            $.get('{{ route("utility.master-history") }}', function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(upload => {
                        html += `<tr>
                            <td>${upload.created_at}</td>
                            <td>${upload.file_name}</td>
                            <td><span class="kpi-highlight">${upload.type}</span></td>
                            <td style="color: green;">✓ Completed</td>
                            <td>${upload.records}</td>
                        </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="5" style="text-align: center;">No uploads yet</td></tr>';
                }
                $('#uploadHistoryTable').html(html);
            });
        }

        // Load history on page load
        loadUploadHistory();

        // File input triggers
        document.getElementById('storeFile')?.addEventListener('change', function(e) {
            if(this.files[0]) $('#storeUploadStatus').html(`<p style="color: #1d6988;">Selected: ${this.files[0].name}</p>`);
        });
        document.getElementById('supervisorFile')?.addEventListener('change', function(e) {
            if(this.files[0]) $('#supervisorUploadStatus').html(`<p style="color: #1d6988;">Selected: ${this.files[0].name}</p>`);
        });
        document.getElementById('companyFile')?.addEventListener('change', function(e) {
            if(this.files[0]) $('#companyUploadStatus').html(`<p style="color: #1d6988;">Selected: ${this.files[0].name}</p>`);
        });
        document.getElementById('mtnFile')?.addEventListener('change', function(e) {
            if(this.files[0]) $('#mtnUploadStatus').html(`<p style="color: #1d6988;">Selected: ${this.files[0].name}</p>`);
        });

        // Style file input buttons
        $('.upload-btn').css('cursor', 'pointer');
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
@endsection
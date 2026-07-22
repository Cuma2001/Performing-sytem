<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Performance Management System')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 16px;
        }

        .sidebar-header .logo {
            width: 180px;
            margin-bottom: 8px;
        }

        .sidebar-header p {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.6);
            margin-top: 4px;
        }

        .nav-menu {
            list-style: none;
            padding: 0 12px;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.08);
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

        .nav-link .badge {
            margin-left: auto;
            background: var(--primary-gold);
            color: #1a2c3e;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Nav Section Title */
        .nav-section-title {
            padding: 16px 16px 8px;
            font-size: 0.65rem;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            letter-spacing: 1px;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 0;
            transition: all 0.3s ease;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 16px 32px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
        }

        .top-bar .page-title h1 {
            font-size: 1.3rem;
            color: var(--primary-teal);
            font-weight: 700;
        }

        .top-bar .page-title small {
            color: #6c757d;
            font-size: 0.8rem;
        }

        .top-bar .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .top-bar .user-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-teal);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .top-bar .user-info .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1a2c3e;
        }

        .top-bar .user-info .user-role {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .logout-btn {
            background: var(--primary-red);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: #c41e26;
        }

        /* Page Content Wrapper */
        .page-wrapper {
            padding: 24px 32px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary-gold);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-card .stat-icon {
            font-size: 1.8rem;
            margin-bottom: 8px;
        }

        .stat-card h3 {
            color: var(--primary-teal);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .chart-card h3 {
            color: var(--primary-teal);
            margin-bottom: 16px;
            font-size: 1rem;
            border-left: 4px solid var(--primary-red);
            padding-left: 12px;
        }

        .chart-container {
            height: 300px;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .table th,
        .table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table th {
            background: #f8fafc;
            color: var(--primary-teal);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tr:hover {
            background: #f8fafc;
        }

        /* Upload Section */
        .upload-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .upload-section h3 {
            color: var(--primary-teal);
            margin-bottom: 16px;
            font-size: 1rem;
            border-left: 4px solid var(--primary-gold);
            padding-left: 12px;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .upload-card {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s;
        }

        .upload-card:hover {
            border-color: var(--primary-teal);
            background: #f8fafc;
        }

        .upload-card i {
            font-size: 40px;
            color: var(--primary-teal);
            margin-bottom: 12px;
        }

        .upload-card h4 {
            color: #1e2f3f;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .upload-card p {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 12px;
        }

        .upload-card input[type="file"] {
            display: none;
        }

        .btn {
            padding: 8px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-teal);
            color: white;
        }

        .btn-primary:hover {
            background: #0e5a75;
        }

        .btn-danger {
            background: var(--primary-red);
            color: white;
        }

        .btn-danger:hover {
            background: #c41e26;
        }

        .btn-success {
            background: #2b7e3a;
            color: white;
        }

        .btn-success:hover {
            background: #1f6b2d;
        }

        .btn-warning {
            background: var(--primary-gold);
            color: #1a2c3e;
        }

        .btn-warning:hover {
            background: #e0b80e;
        }

        .btn-sm {
            padding: 4px 12px;
            font-size: 0.75rem;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary-teal);
            color: var(--primary-teal);
        }

        .btn-outline:hover {
            background: var(--primary-teal);
            color: white;
        }

        /* Status Badges */
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-primary { background: #cce5ff; color: #004085; }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: var(--sidebar-collapsed);
            }
            .sidebar-header .logo,
            .sidebar-header p,
            .nav-link span,
            .nav-section-title {
                display: none;
            }
            .nav-link {
                justify-content: center;
                padding: 12px;
            }
            .nav-link i {
                margin-right: 0;
                font-size: 1.3rem;
            }
            .main-content {
                margin-left: var(--sidebar-collapsed);
            }
            .top-bar {
                padding: 12px 16px;
                flex-wrap: wrap;
                gap: 8px;
            }
            .top-bar .page-title h1 {
                font-size: 1rem;
            }
            .page-wrapper {
                padding: 16px;
            }
            .chart-grid {
                grid-template-columns: 1fr;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .upload-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .top-bar .user-info .user-name {
                display: none;
            }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo">
            <p>Performance Management System</p>
        </div>

        @php
            $user = auth()->user();
            $role = $user ? DB::table('roles')->find($user->role_id) : null;
            $roleName = $role ? $role->name : 'Guest';
        @endphp

        <ul class="nav-menu">
            <!-- Dashboard Section -->

            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Management Section - CEO/HR & Superadmin only -->
            @if(in_array($roleName, ['Superadmin', 'CEO/HR']))

                <li class="nav-item">
                    <a href="{{ route('regions.index') }}" class="nav-link {{ request()->routeIs('regions.*') ? 'active' : '' }}">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Regions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('stores.index') }}" class="nav-link {{ request()->routeIs('stores.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Stores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        <i class="fas fa-id-card"></i>
                        <span>Employees</span>
                    </a>
                </li>
            @endif

            <!-- KPI Section - Superadmin & Supervisor only -->
            @if(in_array($roleName, ['Superadmin', 'Supervisor']))
                <li class="nav-item">
                    <a href="{{ route('kpi.distribution') }}" class="nav-link {{ request()->routeIs('kpi.distribution') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>KPI Distribution</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kpi.upload') }}" class="nav-link {{ request()->routeIs('kpi.upload') ? 'active' : '' }}">
                        <i class="fas fa-upload"></i>
                        <span>KPI Upload</span>
                    </a>
                </li>
            @endif

            <!-- Utility Section -->
            <!-- <li class="nav-item">
                <a href="{{ route('utilities.index') }}" class="nav-link {{ request()->routeIs('utilities.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Utilities</span>
                </a>
            </li> -->

            <!-- Reports Section -->
            <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Reports</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="page-title">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <small>@yield('page-subtitle', 'Performance Management System')</small>
            </div>
            <div class="user-info">
                <div>
                    <div class="user-name">{{ auth()->user()->name ?? 'Guest' }}</div>
                    <div class="user-role">{{ $roleName ?? 'Guest' }}</div>
                </div>
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}</div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <div class="page-wrapper">
            @if(session('success'))
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #155724;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #721c24;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Auto close mobile menu on link click
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        // Optional: close sidebar on mobile
                    }
                });
            });

            // Active link highlighting
            const currentUrl = window.location.pathname;
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentUrl.includes(href) && href !== '/') {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

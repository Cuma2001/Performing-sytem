<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Performance Management System')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
        }
        .navbar {
            background: white;
            padding: 16px 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links a {
            color: #1d6988;
            text-decoration: none;
            margin-left: 24px;
        }
        .logout-btn {
            background: #e5222b;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div style="font-weight: 700; color: #1d6988; font-size: 1.2rem;">
            <img src="{{ asset('assets/images/Screenshot_2026-02-10_085819-removebg-preview.png') }}" alt="Logo" style="width: 190px; margin-bottom: 8px;">
        </div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>
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
    <main>
        @yield('content')
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
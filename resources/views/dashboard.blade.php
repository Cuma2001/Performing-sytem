{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid py-4">

    {{-- Welcome Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <h1 class="fw-bold mb-1 content-center">
                            Welcome, {{ auth()->user()->name }}
                        </h1>

                        <p class="text-muted mb-0">
                            Performance Management Dashboard
                        </p>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-primary px-3 py-2">
                            {{ auth()->user()->role ?? 'User' }}
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- KPI Summary Cards --}}
    <div class="row g-4 mb-4">

        {{-- Overall KPI --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted">Overall KPI Score</h6>

                            <h2 class="fw-bold text-primary">
                                {{ number_format($avgScore ?? 0, 1) }}%
                            </h2>
                        </div>

                        <div class="icon-box bg-primary text-white">
                            <i class="fas fa-chart-line"></i>
                        </div>

                    </div>

                    <div class="progress mt-3" style="height:8px;">
                        <div class="progress-bar bg-primary"
                             role="progressbar"
                             style="width: {{ $avgScore ?? 0 }}%">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Performance Rating --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    @php

                        $rating = 'Poor';
                        $color = 'danger';

                        if(($avgScore ?? 0) >= 80){
                            $rating = 'Excellent';
                            $color = 'success';
                        }
                        elseif(($avgScore ?? 0) >= 60){
                            $rating = 'Good';
                            $color = 'warning';
                        }

                    @endphp

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted">
                                Performance Rating
                            </h6>

                            <h2 class="fw-bold text-{{ $color }}">
                                {{ $rating }}
                            </h2>
                        </div>

                        <div class="icon-box bg-{{ $color }} text-white">
                            <i class="fas fa-award"></i>
                        </div>

                    </div>

                    <small class="text-muted">
                        Based on current KPI performance
                    </small>

                </div>
            </div>
        </div>

        {{-- Sales KPI --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted">
                                Sales KPI
                            </h6>

                            <h2 class="fw-bold text-success">
                                {{ number_format($salesScore ?? 0, 1) }}%
                            </h2>
                        </div>

                        <div class="icon-box bg-success text-white">
                            <i class="fas fa-shopping-cart"></i>
                        </div>

                    </div>

                    <div class="progress mt-3" style="height:8px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $salesScore ?? 0 }}%">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- General KPI --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted">
                                General KPI
                            </h6>

                            <h2 class="fw-bold text-info">
                                {{ number_format($generalScore ?? 0, 1) }}%
                            </h2>
                        </div>

                        <div class="icon-box bg-info text-white">
                            <i class="fas fa-users"></i>
                        </div>

                    </div>

                    <div class="progress mt-3" style="height:8px;">
                        <div class="progress-bar bg-info"
                             role="progressbar"
                             style="width: {{ $generalScore ?? 0 }}%">
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Charts --}}
    <div class="row g-4">

        {{-- Line Chart --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        Performance Trends
                    </h5>
                </div>

                <div class="card-body">
                    <canvas id="performanceChart" height="100"></canvas>
                </div>

            </div>

        </div>

        {{-- Pie Chart --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        KPI Breakdown
                    </h5>
                </div>

                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>

            </div>

        </div>

    </div>

    {{-- KPI Table --}}
    <div class="row mt-4">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold">
                        Recent KPI Records
                    </h5>

                    <a href="#" class="btn btn-primary btn-sm">
                        View All
                    </a>

                </div>

                <div class="card-body table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>KPI Name</th>
                                <th>Target</th>
                                <th>Actual</th>
                                <th>Score</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($kpis ?? [] as $kpi)

                            <tr>

                                <td>
                                    {{ $kpi->kpi_name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $kpi->target ?? 0 }}
                                </td>

                                <td>
                                    {{ $kpi->actual ?? 0 }}
                                </td>

                                <td>
                                    {{ $kpi->score ?? 0 }}%
                                </td>

                                <td>

                                    @if(($kpi->score ?? 0) >= 80)

                                        <span class="badge bg-success">
                                            Excellent
                                        </span>

                                    @elseif(($kpi->score ?? 0) >= 60)

                                        <span class="badge bg-warning text-dark">
                                            Good
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Poor
                                        </span>

                                    @endif

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center text-muted py-4">

                                    No KPI records found.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    // Line Chart

    const ctx = document.getElementById('performanceChart');

    new Chart(ctx, {

        type: 'line',

        data: {

            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],

            datasets: [{
                label: 'KPI Performance',
                data: [65, 70, 75, 80, 78, 90],
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]

        },

        options: {

            responsive: true,

            plugins: {
                legend: {
                    display: true
                }
            }

        }

    });

    // Pie Chart

    const pieCtx = document.getElementById('pieChart');

    new Chart(pieCtx, {

        type: 'doughnut',

        data: {

            labels: ['Sales KPI', 'General KPI'],

            datasets: [{
                data: [
                    {{ $salesScore ?? 0 }},
                    {{ $generalScore ?? 0 }}
                ],
                borderWidth: 1
            }]

        },

        options: {
            responsive: true
        }

    });

</script>

<style>

    .icon-box{
        width:60px;
        height:60px;
        border-radius:15px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:22px;
    }

    .card{
        border-radius:18px;
    }

    .table th{
        font-size:14px;
    }

    .table td{
        font-size:14px;
    }

</style>

@endsection
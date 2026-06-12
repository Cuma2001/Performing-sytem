@extends('layouts.main')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h2">Salesperson Dashboard</h1>
            <p class="text-muted">Your Sales Performance & Activity</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Assigned Store</h6>
                            <h2 class="mb-0">{{ $userStore?->name ?? 'N/A' }}</h2>
                        </div>
                        <i class="fas fa-store fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Total Sales</h6>
                            <h2 class="mb-0">{{ $personalSalesRecords }}</h2>
                        </div>
                        <i class="fas fa-chart-bar fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Total Revenue</h6>
                            <h2 class="mb-0">R{{ number_format($personalRevenue, 2) }}</h2>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Sales Records</h5>
                </div>
                <div class="card-body">
                    @if($recentSales->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSales as $sale)
                                <tr>
                                    <td>{{ $sale->created_at ? \Carbon\Carbon::parse($sale->created_at)->format('d M Y') : 'N/A' }}</td>
                                    <td>R{{ number_format($sale->amount, 2) }}</td>
                                    <td><span class="badge bg-success">Recorded</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">No sales records yet. Keep up the great work!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Performance Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">✓ Track your daily sales to stay on top of your targets</li>
                        <li class="mb-2">✓ Review recent records for accuracy</li>
                        <li class="mb-2">✓ Contact your supervisor for support or guidance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

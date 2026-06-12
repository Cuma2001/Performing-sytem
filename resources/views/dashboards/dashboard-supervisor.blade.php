@extends('layouts.main')

@section('content')
<div class="container-fluid mt-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h2">Supervisor Dashboard</h1>
            <p class="text-muted">Store Performance & Team Management</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Store</h6>
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
                            <h6 class="card-title text-uppercase mb-0">Team Members</h6>
                            <h2 class="mb-0">{{ $teamMembers }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Store Target</h6>
                            <h2 class="mb-0">R{{ $storeTarget?->target_amount ? number_format($storeTarget->target_amount, 2) : '0.00' }}</h2>
                        </div>
                        <i class="fas fa-bullseye fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Store Performance</h5>
                </div>
                <div class="card-body">
                    @if($storePerformance)
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Achievement:</strong> 
                                @if($storePerformance->achievement_percentage)
                                    {{ number_format($storePerformance->achievement_percentage, 2) }}%
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Variance:</strong> 
                                @if($storePerformance->variance)
                                    R{{ number_format($storePerformance->variance, 2) }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    @else
                    <p class="text-muted">No performance data available yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Team Management -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Team Oversight</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Manage your store team, track performance, and monitor sales records.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

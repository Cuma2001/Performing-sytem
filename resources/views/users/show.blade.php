@extends('layouts.app')
@section('content')
    <div style="max-width: 1000px; margin: 0 auto;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                User Details
                <div>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Full Name</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Email</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Phone</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">ID Number</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $user->id_no ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Role</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">
                            <span class="badge" style="background: {{ $user->role === 'Superadmin' || $user->role === 'CEO/HR' ? 'var(--primary-teal)' : ($user->role === 'Supervisor' ? 'var(--primary-gold)' : '#2b7e3a') }}; color: {{ $user->role === 'Supervisor' ? '#1e2f3f' : 'white' }};">
                                {{ $user->role }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Store</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $user->store ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Created At</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Last Updated</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

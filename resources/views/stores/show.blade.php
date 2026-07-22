@extends('layouts.app')
@section('content')
    <div style="max-width: 1000px; margin: 0 auto;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Store Details</span>
                <div>
                    <a href="{{ route('stores.edit', $store) }}" class="btn btn-secondary">Edit Store</a>
                    <a href="{{ route('stores.index') }}" class="btn btn-primary">Back to Stores</a>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Store Name</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Store Code</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->code }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Region</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->region ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Store Type</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->store_type ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 style="color: #1e2f3f;">Address</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->address ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <h5 style="color: #1e2f3f;">City</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->city ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h5 style="color: #1e2f3f;">State</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->state ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <h5 style="color: #1e2f3f;">Zip Code</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->zip_code ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Phone</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Email</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->email ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Manager Name</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->manager_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Manager User</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->manager ? $store->manager->name : 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Opening Time</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->opening_time ? $store->opening_time->format('H:i') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Closing Time</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->closing_time ? $store->closing_time->format('H:i') : 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Parent Store</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $store->parentStore ? $store->parentStore->name : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Status</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">
                            <span class="badge {{ $store->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $store->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <div style="max-width: 1000px; margin: 0 auto;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                Employee Details
                <div>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to Employees</a>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Full Name</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->full_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Employee Code</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->employee_code }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Email</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Phone</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Mobile</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->mobile ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Position</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->position }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Department</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->department ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Designation</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->designation ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Employment Type</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Store</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->store ? $employee->store->name : 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Region</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->region ? $employee->region->name : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Manager</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->manager ? $employee->manager->full_name : 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Hire Date</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Status</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">
                            <span class="badge" style="background: {{ $employee->status === 'active' ? '#22c55e' : ($employee->status === 'on_leave' ? '#f4c610' : '#ef4444') }}; color: {{ $employee->status === 'on_leave' ? '#1e2f3f' : 'white' }};">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Base Salary</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->base_salary ? 'R ' . number_format($employee->base_salary, 2) : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #1e2f3f;">Commission Rate</h5>
                        <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->commission_rate ? $employee->commission_rate . '%' : '0%' }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 style="color: #1e2f3f;">Address</h5>
                    <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->address ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <h5 style="color: #1e2f3f;">Notes</h5>
                    <p style="font-size: 18px; margin-bottom: 0;">{{ $employee->notes ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

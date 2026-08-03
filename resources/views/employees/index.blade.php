@extends('layouts.app')

@section('page-title', 'Employees')
@section('page-subtitle', 'Maintain staff records and keep the team visible')

@section('content')
    <div class="page-hero">
        <div>
            <div class="page-hero__eyebrow"><i class="fas fa-users"></i> Team oversight</div>
            <h2 class="page-hero__title">Employee directory</h2>
            <p class="page-hero__text">Keep staff records clear, searchable and ready for follow-up with a more structured view.</p>
        </div>
        <div class="page-hero__meta">
            <div class="info-pill"><i class="fas fa-users"></i> {{ $employees->count() }} total</div>
            <div class="info-pill"><i class="fas fa-check-circle"></i> {{ $employees->where('status', 'active')->count() }} active</div>
            <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add employee</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header card-header--flex">
            <div>
                <h3 class="card-title">Current employees</h3>
                <p class="card-subtitle">Review employee records, update details and manage status at a glance.</p>
            </div>
            <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add employee</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($employees->isEmpty())
                <div class="upload-section" style="margin-bottom:0; padding:20px;">
                    <h3>No employees found</h3>
                    <p>Create the first employee profile to begin organizing the workforce.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Store</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td>{{ $employee->employee_code }}</td>
                                    <td>{{ $employee->full_name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>{{ $employee->store ? $employee->store->name : 'N/A' }}</td>
                                    <td>
                                        <span class="badge" style="background: {{ $employee->status === 'active' ? '#22c55e' : ($employee->status === 'on_leave' ? '#f4c610' : '#ef4444') }}; color: {{ $employee->status === 'on_leave' ? '#1e2f3f' : 'white' }};">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline"><i class="fas fa-eye"></i> View</a>
                                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                            <form method="POST" action="{{ route('employees.destroy', $employee) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div style="max-width: 1400px; margin: 0 auto;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-users"></i> Employees</span>
                <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
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
                                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-secondary">View</a>
                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

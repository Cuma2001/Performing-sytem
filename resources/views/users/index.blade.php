@extends('layouts.app')
@section('content')
    <div style="max-width: 1400px; margin: 0 auto;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-users"></i> Users</span>
                <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Store</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge" >
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>{{ $user->store ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-secondary">View</a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
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

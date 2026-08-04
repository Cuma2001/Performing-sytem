@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="card">
    <div class="card-header d-flex">
        <span><i class="fas fa-users"></i> Users</span>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert" style="background: #fee2e2; color: #991b1b; border-color: #fecaca; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #fecaca;">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Store</th>
                    <th style="width: 170px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>

                    <td>{{ $user->email }}</td>

                    <td>{{ $user->phone ?? 'N/A' }}</td>

                    <td>
                        <span class="badge" style="background: #1d6988; color: white;">
                            {{ $user->role }}
                        </span>
                    </td>

                    <td>{{ $user->store ?? 'N/A' }}</td>

                    <td>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-users" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        No users found. <a href="{{ route('users.create') }}">Create your first user</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
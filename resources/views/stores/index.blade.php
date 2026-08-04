@extends('layouts.app')

@section('title', 'Manage Stores')

@section('content')
<div class="card">
    <div class="card-header d-flex">
        <span>Stores</span>
        <a href="{{ route('stores.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Store
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
                    <th>Code</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Manager</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stores as $store)
                <tr>
                    <td>
                        <span class="badge" style="background: #1d6988; color: white;">
                            {{ $store->code }}
                        </span>
                    </td>

                    <td><strong>{{ $store->name }}</strong></td>

                    <td>
                        {{ $store->region ?? $store->city . ', ' . $store->state }}
                    </td>

                    <td>{{ $store->phone }}</td>

                    <td>
                        @if($store->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>

                    <td>
                        {{ $store->manager ? $store->manager->name : ($store->manager_name ?? 'N/A') }}
                    </td>

                    <td>
                        <a href="{{ route('stores.edit', $store) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('stores.destroy', $store) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to deactivate this store?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-store" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        No stores found. <a href="{{ route('stores.create') }}">Create your first store</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $stores->links() }}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Manage Regions')

@section('content')
<div class="card">
    <div class="card-header d-flex">
        <span>Regions</span>
        <a href="{{ route('region.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Region
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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Stores Count</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($regions as $region)
                <tr>
                    <td>{{ $region->id }}</td>
                    <td><strong>{{ $region->name }}</strong></td>
                    <td><span class="badge" style="background: #1d6988; color: white;">{{ $region->code }}</span></td>
                    <td>{{ $region->stores_count }}</td>
                    <td>
                        @if($region->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $region->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('region.edit', $region) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('region.destroy', $region) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this region?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-store" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        No regions found. <a href="{{ route('region.create') }}">Create your first region</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $regions->links() }}
    </div>
</div>
@endsection
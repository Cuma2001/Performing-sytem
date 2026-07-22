@extends('layouts.app')
@section('content')
    <div style="max-width: 1400px; margin: 0 auto;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Stores</span>
                <a href="{{ route('stores.create') }}" class="btn btn-primary">Create Store</a>
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
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Manager</th>
                                <th>Parent Store</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                                <tr>
                                    <td>{{ $store->code }}</td>
                                    <td>{{ $store->name }}</td>
                                    <td>{{ $store->region ?? $store->city . ', ' . $store->state }}</td>
                                    <td>{{ $store->phone }}</td>
                                    <td>{{ $store->store_type ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $store->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $store->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $store->manager ? $store->manager->name : ($store->manager_name ?? 'N/A') }}</td>
                                    <td>{{ $store->parentStore ? $store->parentStore->name : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('stores.edit', $store) }}" class="btn btn-sm btn-secondary">Edit</a>
                                        <form method="POST" action="{{ route('stores.destroy', $store) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Deactivate</button>
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

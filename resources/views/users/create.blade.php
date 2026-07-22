@extends('layouts.app')

@section('title', 'Add User')
@section('page-title', 'Add User')
@section('page-subtitle', 'Create a new user')

@section('content')
<div class="user-create">
    <div class="chart-card" style="max-width: 1000px; margin: 0 auto;">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="name" style="display: block; font-weight: 600; margin-bottom: 4px;">Full Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('name') }}">
                    @error('name') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="email" style="display: block; font-weight: 600; margin-bottom: 4px;">Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('email') }}">
                    @error('email') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="phone" style="display: block; font-weight: 600; margin-bottom: 4px;">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="id_no" style="display: block; font-weight: 600; margin-bottom: 4px;">ID Number</label>
                    <input type="text" name="id_no" id="id_no" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('id_no') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="role" style="display: block; font-weight: 600; margin-bottom: 4px;">Role <span style="color: red;">*</span></label>
                    <select name="role" id="role" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                        <option value="">Select Role</option>
                        <option value="Superadmin" {{ old('role') === 'Superadmin' ? 'selected' : '' }}>Superadmin</option>
                        <option value="CEO/HR" {{ old('role') === 'CEO/HR' ? 'selected' : '' }}>CEO/HR</option>
                        <option value="Supervisor" {{ old('role') === 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="Salesperson" {{ old('role') === 'Salesperson' ? 'selected' : '' }}>Salesperson</option>
                    </select>
                    @error('role') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="store_id" style="display: block; font-weight: 600; margin-bottom: 4px;">Store</label>
                    <select name="store_id" id="store_id" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <option value="">No Store</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="password" style="display: block; font-weight: 600; margin-bottom: 4px;">Password <span style="color: red;">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                    @error('password') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="password_confirmation" style="display: block; font-weight: 600; margin-bottom: 4px;">Confirm Password <span style="color: red;">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">Save User</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
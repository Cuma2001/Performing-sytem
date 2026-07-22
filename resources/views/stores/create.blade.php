@extends('layouts.app')

@section('title', 'Add Store')
@section('page-title', 'Add Store')
@section('page-subtitle', 'Create a new store')

@section('content')
<div class="store-create">
    <div class="chart-card" style="max-width: 1000px; margin: 0 auto;">
        <form action="{{ route('stores.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="name" style="display: block; font-weight: 600; margin-bottom: 4px;">Store Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('name') }}">
                    @error('name') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="code" style="display: block; font-weight: 600; margin-bottom: 4px;">Store Code <span style="color: red;">*</span></label>
                    <input type="text" name="code" id="code" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('code') }}">
                    @error('code') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="region" style="display: block; font-weight: 600; margin-bottom: 4px;">Region</label>
                    <input type="text" name="region" id="region" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('region') }}">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="store_type" style="display: block; font-weight: 600; margin-bottom: 4px;">Store Type</label>
                    <select name="store_type" id="store_type" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <option value="">Select Type</option>
                        <option value="Franchise" {{ old('store_type') === 'Franchise' ? 'selected' : '' }}>Franchise</option>
                        <option value="Company Owned" {{ old('store_type') === 'Company Owned' ? 'selected' : '' }}>Company Owned</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="address" style="display: block; font-weight: 600; margin-bottom: 4px;">Address</label>
                <textarea name="address" id="address" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" rows="3">{{ old('address') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4" style="margin-bottom: 16px;">
                    <label for="city" style="display: block; font-weight: 600; margin-bottom: 4px;">City</label>
                    <input type="text" name="city" id="city" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('city') }}">
                </div>
                <div class="col-md-4" style="margin-bottom: 16px;">
                    <label for="state" style="display: block; font-weight: 600; margin-bottom: 4px;">State/Province</label>
                    <input type="text" name="state" id="state" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('state') }}">
                </div>
                <div class="col-md-4" style="margin-bottom: 16px;">
                    <label for="zip_code" style="display: block; font-weight: 600; margin-bottom: 4px;">Zip Code</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('zip_code') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="phone" style="display: block; font-weight: 600; margin-bottom: 4px;">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="email" style="display: block; font-weight: 600; margin-bottom: 4px;">Email</label>
                    <input type="email" name="email" id="email" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('email') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="manager_name" style="display: block; font-weight: 600; margin-bottom: 4px;">Manager Name</label>
                    <input type="text" name="manager_name" id="manager_name" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('manager_name') }}">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="manager_id" style="display: block; font-weight: 600; margin-bottom: 4px;">Manager User</label>
                    <select name="manager_id" id="manager_id" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <option value="">Select Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div style="margin-bottom: 16px;">
                <label for="parent_store_id" style="display: block; font-weight: 600; margin-bottom: 4px;">Parent Store</label>
                <select name="parent_store_id" id="parent_store_id" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                    <option value="">None</option>
                    @foreach($parentStores as $parentStore)
                        <option value="{{ $parentStore->id }}" {{ old('parent_store_id') == $parentStore->id ? 'selected' : '' }}>{{ $parentStore->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 4px;">Status</label>
                <div style="display: flex; gap: 16px;">
                    <label>
                        <input type="checkbox" name="is_active" id="is_active" checked> Active
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">Create Store</button>
                <a href="{{ route('stores.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

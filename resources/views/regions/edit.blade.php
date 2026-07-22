@extends('layouts.app')

@section('title', 'Edit Region')
@section('page-title', 'Edit Region')
@section('page-subtitle', 'Update region information')

@section('content')
<div class="region-edit">
    <div class="chart-card" style="max-width: 1000px; margin: 0 auto;">
        <form action="{{ route('region.update', $region->id ?? $region) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="code" style="display: block; font-weight: 600; margin-bottom: 4px;">Region Code <span style="color: red;">*</span></label>
                    <input type="text" name="code" id="code" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('code', $region->code ?? '') }}" required>
                    @error('code') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="name" style="display: block; font-weight: 600; margin-bottom: 4px;">Region Name <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('name', $region->name ?? '') }}" required>
                    @error('name') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="country" style="display: block; font-weight: 600; margin-bottom: 4px;">Country</label>
                    <input type="text" name="country" id="country" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('country', $region->country ?? '') }}">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="city" style="display: block; font-weight: 600; margin-bottom: 4px;">City</label>
                    <input type="text" name="city" id="city" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('city', $region->city ?? '') }}">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="description" style="display: block; font-weight: 600; margin-bottom: 4px;">Description</label>
                <textarea name="description" id="description" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" rows="3">{{ old('description', $region->description ?? '') }}</textarea>
                @error('description') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 4px;">Status</label>
                <div style="display: flex; gap: 16px;">
                    <label>
                        <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $region->is_active ?? true) ? 'checked' : '' }}> Active
                    </label>
                </div>
                @error('is_active') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">Update Region</button>
                <a href="{{ route('region.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
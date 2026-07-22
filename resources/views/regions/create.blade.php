@extends('layouts.app')

@section('title', 'Add Region')
@section('page-title', 'Add Region')
@section('page-subtitle', 'Create a new region')

@section('content')
<div class="region-create">
    <div class="chart-card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('regions.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 16px;">
                <label for="code" style="display: block; font-weight: 600; margin-bottom: 4px;">Region Code <span style="color: red;">*</span></label>
                <input type="text" name="code" id="code" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                @error('code') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label for="name" style="display: block; font-weight: 600; margin-bottom: 4px;">Region Name <span style="color: red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                @error('name') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label for="country" style="display: block; font-weight: 600; margin-bottom: 4px;">Country</label>
                <input type="text" name="country" id="country" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label for="city" style="display: block; font-weight: 600; margin-bottom: 4px;">City</label>
                <input type="text" name="city" id="city" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label for="state" style="display: block; font-weight: 600; margin-bottom: 4px;">State/Province</label>
                <input type="text" name="state" id="state" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 4px;">Status</label>
                <div style="display: flex; gap: 16px;">
                    <label>
                        <input type="radio" name="is_active" value="1" checked> Active
                    </label>
                    <label>
                        <input type="radio" name="is_active" value="0"> Inactive
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">Create Region</button>
                <a href="{{ route('regions.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')
@section('page-subtitle', 'Update employee information')

@section('content')
<div class="employee-edit">
    <div class="chart-card" style="max-width: 1000px; margin: 0 auto;">
        <form method="POST" action="{{ route('employees.update', $employee) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="employee_code" style="display: block; font-weight: 600; margin-bottom: 4px;">Employee Code <span style="color: red;">*</span></label>
                    <input type="text" name="employee_code" id="employee_code" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('employee_code', $employee->employee_code) }}">
                    @error('employee_code') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="status" style="display: block; font-weight: 600; margin-bottom: 4px;">Status <span style="color: red;">*</span></label>
                    <select name="status" id="status" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                        <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on_leave" {{ old('status', $employee->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                        <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="first_name" style="display: block; font-weight: 600; margin-bottom: 4px;">First Name <span style="color: red;">*</span></label>
                    <input type="text" name="first_name" id="first_name" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('first_name', $employee->first_name) }}">
                    @error('first_name') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="last_name" style="display: block; font-weight: 600; margin-bottom: 4px;">Last Name <span style="color: red;">*</span></label>
                    <input type="text" name="last_name" id="last_name" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('last_name', $employee->last_name) }}">
                    @error('last_name') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="email" style="display: block; font-weight: 600; margin-bottom: 4px;">Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('email', $employee->email) }}">
                    @error('email') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="phone" style="display: block; font-weight: 600; margin-bottom: 4px;">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('phone', $employee->phone) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="mobile" style="display: block; font-weight: 600; margin-bottom: 4px;">Mobile</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('mobile', $employee->mobile) }}">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="position" style="display: block; font-weight: 600; margin-bottom: 4px;">Position <span style="color: red;">*</span></label>
                    <input type="text" name="position" id="position" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('position', $employee->position) }}">
                    @error('position') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="department" style="display: block; font-weight: 600; margin-bottom: 4px;">Department</label>
                    <input type="text" name="department" id="department" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('department', $employee->department) }}">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="designation" style="display: block; font-weight: 600; margin-bottom: 4px;">Designation</label>
                    <input type="text" name="designation" id="designation" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('designation', $employee->designation) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="employment_type" style="display: block; font-weight: 600; margin-bottom: 4px;">Employment Type <span style="color: red;">*</span></label>
                    <select name="employment_type" id="employment_type" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                        <option value="full_time" {{ old('employment_type', $employee->employment_type) === 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('employment_type', $employee->employment_type) === 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ old('employment_type', $employee->employment_type) === 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="intern" {{ old('employment_type', $employee->employment_type) === 'intern' ? 'selected' : '' }}>Intern</option>
                    </select>
                    @error('employment_type') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="store_id" style="display: block; font-weight: 600; margin-bottom: 4px;">Store <span style="color: red;">*</span></label>
                    <select name="store_id" id="store_id" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                        <option value="">Select Store</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ old('store_id', $employee->store_id) == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                    @error('store_id') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="region_id" style="display: block; font-weight: 600; margin-bottom: 4px;">Region <span style="color: red;">*</span></label>
                    <select name="region_id" id="region_id" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required>
                        <option value="">Select Region</option>
                        @foreach(\App\Models\Region::all() as $region)
                            <option value="{{ $region->id }}" {{ old('region_id', $employee->region_id) == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                    @error('region_id') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="manager_id" style="display: block; font-weight: 600; margin-bottom: 4px;">Manager</label>
                    <select name="manager_id" id="manager_id" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <option value="">No Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $employee->manager_id) == $manager->id ? 'selected' : '' }}>{{ $manager->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="user_id" style="display: block; font-weight: 600; margin-bottom: 4px;">User</label>
                    <select name="user_id" id="user_id" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <option value="">No User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="hire_date" style="display: block; font-weight: 600; margin-bottom: 4px;">Hire Date <span style="color: red;">*</span></label>
                    <input type="date" name="hire_date" id="hire_date" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" required value="{{ old('hire_date', $employee->hire_date) }}">
                    @error('hire_date') <span style="color: red; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="termination_date" style="display: block; font-weight: 600; margin-bottom: 4px;">Termination Date</label>
                    <input type="date" name="termination_date" id="termination_date" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('termination_date', $employee->termination_date) }}">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="base_salary" style="display: block; font-weight: 600; margin-bottom: 4px;">Base Salary</label>
                    <input type="number" name="base_salary" id="base_salary" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('base_salary', $employee->base_salary) }}" step="0.01">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="commission_rate" style="display: block; font-weight: 600; margin-bottom: 4px;">Commission Rate (%)</label>
                    <input type="number" name="commission_rate" id="commission_rate" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('commission_rate', $employee->commission_rate) }}" step="0.01" min="0" max="100">
                </div>
                <div class="col-md-6" style="margin-bottom: 16px;">
                    <label for="bonus_rate" style="display: block; font-weight: 600; margin-bottom: 4px;">Bonus Rate (%)</label>
                    <input type="number" name="bonus_rate" id="bonus_rate" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" value="{{ old('bonus_rate', $employee->bonus_rate) }}" step="0.01" min="0" max="100">
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="address" style="display: block; font-weight: 600; margin-bottom: 4px;">Address</label>
                <textarea name="address" id="address" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" rows="3">{{ old('address', $employee->address) }}</textarea>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="notes" style="display: block; font-weight: 600; margin-bottom: 4px;">Notes</label>
                <textarea name="notes" id="notes" class="form-control" style="width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px;" rows="3">{{ old('notes', $employee->notes) }}</textarea>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">Update Employee</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
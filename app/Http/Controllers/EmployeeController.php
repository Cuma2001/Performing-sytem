<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['store', 'region', 'manager', 'user'])
            ->latest()
            ->get();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $stores = Store::all();
        $users = User::all();
        $managers = Employee::all();

        return view('employees.create', compact('stores', 'users', 'managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|string|max:50|unique:employees,employee_code',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'required|string|max:100',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'store_id' => 'required|exists:stores,id',
            'region_id' => 'required|exists:regions,id',
            'user_id' => 'nullable|exists:users,id',
            'manager_id' => 'nullable|exists:employees,id',
            'hire_date' => 'required|date',
            'termination_date' => 'nullable|date',
            'base_salary' => 'nullable|numeric',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bonus_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,terminated,on_leave',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully!');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $stores = Store::all();
        $users = User::all();
        $managers = Employee::where('id', '!=', $employee->id)->get();

        return view('employees.edit', compact('employee', 'stores', 'users', 'managers'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_code' => 'required|string|max:50|unique:employees,employee_code,' . $employee->id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'required|string|max:100',
            'department' => 'nullable|string|max:100',
            'designation' => 'nullable|string|max:100',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'store_id' => 'required|exists:stores,id',
            'region_id' => 'required|exists:regions,id',
            'user_id' => 'nullable|exists:users,id',
            'manager_id' => 'nullable|exists:employees,id',
            'hire_date' => 'required|date',
            'termination_date' => 'nullable|date',
            'base_salary' => 'nullable|numeric',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'bonus_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,terminated,on_leave',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully!');
    }
}

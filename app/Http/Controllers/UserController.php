<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['store'])->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $stores = Store::all();
        return view('users.create', compact('stores'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'nullable|string|max:20',
        'id_no' => 'nullable|string|max:20',
        'role' => 'required|string|in:Superadmin,CEO/HR,Supervisor,Salesperson',
        'store_id' => 'nullable|exists:stores,id',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'id_no' => $request->id_no,
        'store_id' => $request->store_id,
        'store' => $request->store_id ? Store::find($request->store_id)->name : null,
        'password' => Hash::make($request->password),
    ]);

    $user->assignRole($request->role);

    return redirect()->route('users.index')->with('success', 'User created successfully!');
}

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $stores = Store::all();
        return view('users.edit', compact('user', 'stores'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'id_no' => 'nullable|string|max:20',
            'role' => 'required|string|in:Superadmin,CEO/HR,Supervisor,Salesperson',
            'store_id' => 'nullable|exists:stores,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'id_no' => $request->id_no,
            'role' => $request->role,
            'store_id' => $request->store_id,
            'store' => $request->store_id ? Store::find($request->store_id)->name : null,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}

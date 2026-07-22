<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with(['parentStore', 'manager'])->latest()->get();

        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        $parentStores = Store::all();
        $managers = User::all();

        return view('stores.create', compact('parentStores', 'managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:30|unique:stores,code',
            'region' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'manager_name' => 'nullable|string|max:100',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
            'store_type' => 'nullable|in:Franchise,Company Owned',
            'parent_store_id' => 'nullable|exists:stores,id',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $store = Store::create([
            'name' => $request->name,
            'code' => $request->code,
            'region' => $request->region,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager_name' => $request->manager_name,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'is_active' => $request->has('is_active'),
            'store_type' => $request->store_type,
            'parent_store_id' => $request->parent_store_id,
            'manager_id' => $request->manager_id,
        ]);

        return redirect()->route('stores.show', $store)
            ->with('success', 'Store created successfully');
    }

    public function show(Store $store)
    {
        return view('stores.show', compact('store'));
    }

    public function edit(Store $store)
    {
        $parentStores = Store::where('id', '!=', $store->id)->get();
        $managers = User::all();

        return view('stores.edit', compact('store', 'parentStores', 'managers'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:30|unique:stores,code,' . $store->id,
            'region' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'manager_name' => 'nullable|string|max:100',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
            'store_type' => 'nullable|in:Franchise,Company Owned',
            'parent_store_id' => 'nullable|exists:stores,id',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $store->update([
            'name' => $request->name,
            'code' => $request->code,
            'region' => $request->region,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager_name' => $request->manager_name,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'is_active' => $request->has('is_active'),
            'store_type' => $request->store_type,
            'parent_store_id' => $request->parent_store_id,
            'manager_id' => $request->manager_id,
        ]);

        return redirect()->route('stores.show', $store)
            ->with('success', 'Store updated successfully');
    }

    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store deactivated successfully');
    }
}

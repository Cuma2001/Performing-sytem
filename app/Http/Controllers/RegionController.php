<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::orderBy('name')->paginate(15);
        return view('regions.index', compact('regions'));
    }

    public function create()
    {
        return view('regions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:regions,code',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // Ensure is_active is properly set as boolean
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Region::create($validated);

        // FIX: Changed from 'region.index' to 'regions.index'
        return redirect()->route('regions.index')
            ->with('success', 'Region created successfully.');
    }

    public function edit(Region $region)
    {
        return view('regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('regions')->ignore($region->id)],
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // Ensure is_active is properly set as boolean
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $region->update($validated);

        // FIX: Changed from 'region.index' to 'regions.index'
        return redirect()->route('regions.index')
            ->with('success', 'Region updated successfully.');
    }

    public function destroy(Region $region)
    {
        if ($region->stores()->count() > 0) {
            // FIX: Changed from 'region.index' to 'regions.index'
            return redirect()->route('regions.index')
                ->with('error', 'Cannot delete region because it has associated stores.');
        }

        $region->delete();

        // FIX: Changed from 'region.index' to 'regions.index'
        return redirect()->route('regions.index')
            ->with('success', 'Region deleted successfully.');
    }

    /**
     * Toggle region active status.
     */
    public function toggleStatus(Region $region)
    {
        $region->is_active = !$region->is_active;
        $region->save();

        $status = $region->is_active ? 'activated' : 'deactivated';
        return redirect()->route('regions.index')
            ->with('success', "Region {$status} successfully!");
    }
}
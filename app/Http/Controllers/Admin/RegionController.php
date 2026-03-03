<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::ordered()->get();
        return view('pages.admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('pages.admin.regions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:regions,name',
            'code' => 'nullable|string|max:20|unique:regions,code',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Region::create($validated);

        return redirect()->route('admin.regions.index')
            ->with('success', __('admin.region_created'));
    }

    public function edit(Region $region)
    {
        return view('pages.admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('regions')->ignore($region->id),
            ],
            'code' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('regions')->ignore($region->id),
            ],
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? $region->sort_order;

        $region->update($validated);

        return redirect()->route('admin.regions.index')
            ->with('success', __('admin.region_updated'));
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index')
            ->with('success', __('admin.region_deleted'));
    }
}

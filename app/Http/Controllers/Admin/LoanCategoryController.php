<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Services\LogService;

class LoanCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = LoanCategory::ordered()->get();
        return view('pages.admin.loan-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.loan-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:loan_categories,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category = LoanCategory::create($validated);

        // Log activity
        LogService::logLoanCategoryCreated($category);

        return redirect()->route('admin.loan-categories.index')
            ->with('success', 'Loan category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanCategory $loanCategory)
    {
        return view('pages.admin.loan-categories.show', compact('loanCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanCategory $loanCategory)
    {
        return view('pages.admin.loan-categories.edit', compact('loanCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanCategory $loanCategory)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('loan_categories')->ignore($loanCategory->id),
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? $loanCategory->sort_order;

        $oldValues = $loanCategory->toArray();
        $loanCategory->update($validated);
        $newValues = $loanCategory->fresh()->toArray();

        // Log activity
        LogService::logLoanCategoryUpdated($loanCategory, $oldValues, $newValues);

        return redirect()->route('admin.loan-categories.index')
            ->with('success', 'Loan category updated successfully.');
    }

    /**
     * Disable the specified loan category.
     */
    public function disable(Request $request, LoanCategory $loanCategory)
    {
        // Validate password
        $request->validate([
            'password' => 'required|string',
        ]);

        // Verify password
        if (!Hash::check($request->password, auth()->user()->password)) {
            return redirect()->route('admin.loan-categories.index')
                ->with('error', 'Invalid password. Please try again.');
        }

        // Check if category is already disabled
        if (!$loanCategory->is_active) {
            return redirect()->route('admin.loan-categories.index')
                ->with('error', 'Category is already disabled.');
        }

        // Disable the category
        $loanCategory->update([
            'is_active' => false
        ]);

        // Log activity
        LogService::logLoanCategoryDisabled($loanCategory);

        return redirect()->route('admin.loan-categories.index')
            ->with('success', 'Loan category disabled successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        LogService::log(
            'loan_category_created',
            auth()->user(),
            $category,
            null,
            $validated,
            'medium',
            "Loan category '{$category->name}' created"
        );

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

        // Log activity
        LogService::log(
            'loan_category_updated',
            auth()->user(),
            $loanCategory,
            $oldValues,
            $loanCategory->fresh()->toArray(),
            'medium',
            "Loan category '{$loanCategory->name}' updated"
        );

        return redirect()->route('admin.loan-categories.index')
            ->with('success', 'Loan category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanCategory $loanCategory)
    {
        // Check if category is being used by any loan products
        $productsCount = \App\Models\LoanProduct::where('loan_category_id', $loanCategory->id)->count();
        
        if ($productsCount > 0) {
            return redirect()->route('admin.loan-categories.index')
                ->with('error', "Cannot delete category. It is being used by {$productsCount} loan product(s).");
        }

        $categoryName = $loanCategory->name;
        $categoryData = $loanCategory->toArray();
        
        $loanCategory->delete();

        // Log activity
        LogService::log(
            'loan_category_deleted',
            auth()->user(),
            null,
            $categoryData,
            null,
            'medium',
            "Loan category '{$categoryName}' deleted"
        );

        return redirect()->route('admin.loan-categories.index')
            ->with('success', 'Loan category deleted successfully.');
    }
}

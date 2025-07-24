<?php

namespace App\Livewire\LoanProduct;

use App\Models\LoanProduct;
use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;

class LoanProductManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $statusFilter = 'all';
    public $employmentFilter = 'all';
    
    // Modal states
    public $showDeleteModal = false;
    public $showActivateModal = false;
    public $selectedProductId = null;
    public $selectedProductName = '';

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $lenderId = auth()->user()->lender?->id;
        
        if (!$lenderId) {
            return view('livewire.loan-product.dashboard', [
                'products' => collect(),
                'stats' => []
            ]);
        }

        $products = LoanProduct::where('lender_id', $lenderId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('product_code', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->when($this->employmentFilter !== 'all', function ($query) {
                $query->where('employment_requirement', $this->employmentFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => LoanProduct::where('lender_id', $lenderId)->count(),
            'active' => LoanProduct::where('lender_id', $lenderId)->where('is_active', true)->count(),
            'inactive' => LoanProduct::where('lender_id', $lenderId)->where('is_active', false)->count(),
            'total_applications' => Application::whereHas('loanProduct', function($q) use ($lenderId) {
                $q->where('lender_id', $lenderId);
            })->count(),
        ];  

        return view('livewire.loan-product.Loan-product-management', [
            'products' => $products,
            'stats' => $stats
        ]);
    }

    public function showCreateForm()
    {
        return redirect()->route('loan-products.create');
    }

    public function editProduct($id)
    {
        return redirect()->route('loan-products.edit', $id);
    }

    public function viewProduct($id)
    {
        return redirect()->route('loan-products.show', $id);
    }

    // Modal actions
    public function confirmDelete($id, $name)
    {
        $this->selectedProductId = $id;
        $this->selectedProductName = $name;
        $this->showDeleteModal = true;
    }

    public function confirmActivate($id, $name)
    {
        $this->selectedProductId = $id;
        $this->selectedProductName = $name;
        $this->showActivateModal = true;
    }

    public function deleteProduct()
    {
        if ($this->selectedProductId) {
            $product = LoanProduct::findOrFail($this->selectedProductId);
            $product->delete();
            
            session()->flash('message', 'Product deleted successfully!');
            $this->closeDeleteModal();
            $this->resetPage();
        }
    }

    public function toggleProductStatus()
    {
        if ($this->selectedProductId) {
            $product = LoanProduct::findOrFail($this->selectedProductId);
            $product->update(['is_active' => !$product->is_active]);
            
            $status = $product->is_active ? 'activated' : 'deactivated';
            session()->flash('message', "Product {$status} successfully!");
            $this->closeActivateModal();
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedProductId = null;
        $this->selectedProductName = '';
    }

    public function closeActivateModal()
    {
        $this->showActivateModal = false;
        $this->selectedProductId = null;
        $this->selectedProductName = '';
    }

    // Reactive updates
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingEmploymentFilter()
    {
        $this->resetPage();
    }
}
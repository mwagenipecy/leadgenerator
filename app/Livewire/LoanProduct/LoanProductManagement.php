<?php

namespace App\Livewire\LoanProduct;

use App\Models\LoanProduct;
use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\LogService;

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

    public function mount()
    {
        $user = auth()->user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            abort(403, 'Only lenders can access loan products.');
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            abort(403, 'You must be associated with a lender to access loan products.');
        }
    }

    public function render()
    {
        $user = auth()->user();
        $lenderId = $user->lender?->id;
        
        if (!$lenderId) {
            return view('livewire.loan-product.dashboard', [
                'products' => collect(),
                'stats' => []
            ]);
        }

        $products = LoanProduct::where('lender_id', $lenderId)
            ->where('status', '!=', 'deleted')
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
            'total' => LoanProduct::where('lender_id', $lenderId)->where('status', '!=', 'deleted')->count(),
            'active' => LoanProduct::where('lender_id', $lenderId)->where('is_active', true)->where('status', '!=', 'deleted')->count(),
            'inactive' => LoanProduct::where('lender_id', $lenderId)->where('is_active', false)->where('status', '!=', 'deleted')->count(),
            'total_applications' => Application::whereHas('loanProduct', function($q) use ($lenderId) {
                $q->where('lender_id', $lenderId)->where('status', '!=', 'deleted');
            })->count(),
        ];  

        return view('livewire.loan-product.loan-product-management', [
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
        $user = auth()->user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            session()->flash('error', 'Only lenders can edit loan products.');
            return;
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            session()->flash('error', 'You must be associated with a lender to edit loan products.');
            return;
        }
        
        // Check if the product belongs to the user's lender
        $product = LoanProduct::where('id', $id)
            ->where('lender_id', $user->lender->id)
            ->where('status', '!=', 'deleted')
            ->first();
        
        if (!$product) {
            session()->flash('error', 'Product not found or you do not have permission to edit it.');
            return;
        }
        
        // Check if lender has products (at least one product exists)
        $hasProducts = LoanProduct::where('lender_id', $user->lender->id)
            ->where('status', '!=', 'deleted')
            ->exists();
        
        if (!$hasProducts) {
            session()->flash('error', 'You must have at least one product to perform this operation.');
            return;
        }
        
        return redirect()->route('loan-products.edit', $id);
    }

    public function viewProduct($id)
    {
        $user = auth()->user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            session()->flash('error', 'Only lenders can view loan products.');
            return;
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            session()->flash('error', 'You must be associated with a lender to view loan products.');
            return;
        }
        
        // Check if the product belongs to the user's lender
        $product = LoanProduct::where('id', $id)
            ->where('lender_id', $user->lender->id)
            ->where('status', '!=', 'deleted')
            ->first();
        
        if (!$product) {
            session()->flash('error', 'Product not found or you do not have permission to view it.');
            return;
        }
        
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
        $user = auth()->user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            session()->flash('error', 'Only lenders can delete loan products.');
            $this->closeDeleteModal();
            return;
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            session()->flash('error', 'You must be associated with a lender to delete loan products.');
            $this->closeDeleteModal();
            return;
        }
        
        if ($this->selectedProductId) {
            // Check if the product belongs to the user's lender
            $product = LoanProduct::where('id', $this->selectedProductId)
                ->where('lender_id', $user->lender->id)
                ->where('status', '!=', 'deleted')
                ->first();
            
            if (!$product) {
                session()->flash('error', 'Product not found or you do not have permission to delete it.');
                $this->closeDeleteModal();
                return;
            }
            
            // Check if lender has products (at least one product exists)
            $hasProducts = LoanProduct::where('lender_id', $user->lender->id)
                ->where('status', '!=', 'deleted')
                ->where('id', '!=', $this->selectedProductId)
                ->exists();
            
            if (!$hasProducts) {
                session()->flash('error', 'You must have at least one product remaining. Cannot delete the last product.');
                $this->closeDeleteModal();
                return;
            }
            
            $product->update([
                'status' => 'deleted',
                'is_active' => false,
                'updated_by' => auth()->id()
            ]);
            
            // Log the deletion
            if (class_exists(LogService::class)) {
                LogService::logLoanProductDeleted($product);
            }
            
            session()->flash('message', 'Product deleted successfully!');
            $this->closeDeleteModal();
            $this->resetPage();
        }
    }

    public function toggleProductStatus()
    {
        $user = auth()->user();
        
        // Check if user is a lender
        if (!$user->isLender() && !$user->hasRole('lender')) {
            session()->flash('error', 'Only lenders can change product status.');
            $this->closeActivateModal();
            return;
        }
        
        // Check if user has a lender association
        if (!$user->lender) {
            session()->flash('error', 'You must be associated with a lender to change product status.');
            $this->closeActivateModal();
            return;
        }
        
        if ($this->selectedProductId) {
            // Check if the product belongs to the user's lender
            $product = LoanProduct::where('id', $this->selectedProductId)
                ->where('lender_id', $user->lender->id)
                ->where('status', '!=', 'deleted')
                ->first();
            
            if (!$product) {
                session()->flash('error', 'Product not found or you do not have permission to change its status.');
                $this->closeActivateModal();
                return;
            }
            
            // Check if lender has products (at least one product exists)
            $hasProducts = LoanProduct::where('lender_id', $user->lender->id)
                ->where('status', '!=', 'deleted')
                ->exists();
            
            if (!$hasProducts) {
                session()->flash('error', 'You must have at least one product to perform this operation.');
                $this->closeActivateModal();
                return;
            }
            
            $oldStatus = $product->is_active;
            $product->update([
                'is_active' => !$product->is_active,
                'status' => !$product->is_active ? 'active' : 'inactive',
                'updated_by' => auth()->id()
            ]);
            $product->refresh();
            
            // Log the status change
            if (class_exists(LogService::class)) {
                LogService::logLoanProductStatusChanged($product, $product->is_active);
            }
            
            $status = $product->is_active ? 'activated' : 'deactivated';
            session()->flash('message', "Product {$status} successfully!");
            $this->closeActivateModal();
            $this->resetPage();
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
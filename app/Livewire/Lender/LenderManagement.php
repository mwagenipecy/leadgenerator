<?php

namespace App\Livewire\Lender;

use App\Models\Lender;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LenderManagement extends Component
{
    use WithFileUploads, WithPagination;

    // Properties for form
    #[Rule('required|string|max:255')]
    public $company_name = '';

    #[Rule('nullable|string|max:255')]
    public $license_number = '';

    #[Rule('required|string|max:255')]
    public $contact_person = '';

    #[Rule('required|email|max:255')]
    public $email = '';

    #[Rule('required|string|max:20')]
    public $phone = '';

    #[Rule('required|string')]
    public $address = '';

    #[Rule('required|string|max:100')]
    public $city = '';

    #[Rule('required|string|max:100')]
    public $region = '';

    #[Rule('nullable|string|max:20')]
    public $postal_code = '';

    #[Rule('nullable|url|max:255')]
    public $website = '';

    #[Rule('nullable|string')]
    public $description = '';

    // Document uploads
    #[Rule('nullable|file|mimes:pdf,jpg,jpeg,png|max:5120')]
    public $business_license;

    #[Rule('nullable|file|mimes:pdf,jpg,jpeg,png|max:5120')]
    public $tax_certificate;

    #[Rule('nullable|file|mimes:pdf,jpg,jpeg,png|max:5120')]
    public $bank_statement;

    // Component state
    public $showAddForm = false;
    public $showViewModal = false;
    public $selectedLender = null;
    public $search = '';
    public $statusFilter = 'all';
    public $regionFilter = 'all';
    public $rejection_reason = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        //
    }

    public function render()
    {
        $lenders = Lender::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->regionFilter !== 'all', function ($query) {
                $query->where('region', $this->regionFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $regions = Lender::distinct()->pluck('region')->filter();
        $stats = $this->getStats();

        return view('livewire.lender.lender-management', [
            'lenders' => $lenders,
            'regions' => $regions,
            'stats' => $stats
        ]);
    }

    public function showAddLenderForm()
    {
        $this->showAddForm = true;
        $this->resetForm();
    }

    public function hideAddLenderForm()
    {
        $this->showAddForm = false;
        $this->resetForm();
    }

    public function saveLender()
    {
        $this->validate();

        // Handle file uploads
        $documents = [];
        
        if ($this->business_license) {
            $documents['business_license'] = $this->business_license->store('lender-documents', 'public');
        }
        
        if ($this->tax_certificate) {
            $documents['tax_certificate'] = $this->tax_certificate->store('lender-documents', 'public');
        }
        
        if ($this->bank_statement) {
            $documents['bank_statement'] = $this->bank_statement->store('lender-documents', 'public');
        }

        // Create lender
        Lender::create([
            'company_name' => $this->company_name,
            'license_number' => $this->license_number,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'region' => $this->region,
            'postal_code' => $this->postal_code,
            'website' => $this->website,
            'description' => $this->description,
            'documents' => $documents,
            'status' => 'pending'
        ]);

        $this->hideAddLenderForm();
        session()->flash('message', 'Lender application submitted successfully!');
        $this->resetPage();
    }

    public function viewLender($id)
    {
        $this->selectedLender = Lender::findOrFail($id);
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedLender = null;
    }

    public function approveLender($id)
    {
        $lender = Lender::findOrFail($id);
        
        if ($lender->isPending()) {
            // Create user account
            $user = $lender->createUserAccount(auth()->id());
            
            session()->flash('message', 'Lender approved successfully! User account created.');
        }
    }

    public function rejectLender($id)
    {
        if (empty($this->rejection_reason)) {
            session()->flash('error', 'Please provide a rejection reason.');
            return;
        }

        $lender = Lender::findOrFail($id);
        
        if ($lender->isPending()) {
            $lender->update([
                'status' => 'rejected',
                'rejection_reason' => $this->rejection_reason
            ]);
            
            $this->rejection_reason = '';
            session()->flash('message', 'Lender application rejected.');
        }
    }

    public function suspendLender($id)
    {
        $lender = Lender::findOrFail($id);
        
        if ($lender->isApproved()) {
            $lender->update(['status' => 'suspended']);
            
            // Optionally suspend the user account too
            if ($lender->user) {
                $lender->user->update(['is_active' => false]);
            }
            
            session()->flash('message', 'Lender suspended successfully.');
        }
    }

    public function reactivateLender($id)
    {
        $lender = Lender::findOrFail($id);
        
        if ($lender->isSuspended()) {
            $lender->update(['status' => 'approved']);
            
            // Reactivate user account
            if ($lender->user) {
                $lender->user->update(['is_active' => true]);
            }
            
            session()->flash('message', 'Lender reactivated successfully.');
        }
    }

    public function deleteLender($id)
    {
        $lender = Lender::findOrFail($id);
        
        // Delete uploaded documents
        if ($lender->documents) {
            foreach ($lender->documents as $document) {
                Storage::disk('public')->delete($document);
            }
        }
        
        // Delete associated user if exists
        if ($lender->user) {
            $lender->user->delete();
        }
        
        $lender->delete();
        session()->flash('message', 'Lender deleted successfully.');
    }

    private function resetForm()
    {
        $this->company_name = '';
        $this->license_number = '';
        $this->contact_person = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->city = '';
        $this->region = '';
        $this->postal_code = '';
        $this->website = '';
        $this->description = '';
        $this->business_license = null;
        $this->tax_certificate = null;
        $this->bank_statement = null;
        $this->resetValidation();
    }

    private function getStats()
    {
        return [
            'total' => Lender::count(),
            'pending' => Lender::pending()->count(),
            'approved' => Lender::approved()->count(),
            'rejected' => Lender::rejected()->count(),
            'suspended' => Lender::suspended()->count(),
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingRegionFilter()
    {
        $this->resetPage();
    }
}



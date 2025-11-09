<?php

namespace App\Livewire\Lender;

use App\Models\Lender;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public $showPasswordConfirmModal = false;
    public $selectedLender = null;
    public $search = '';
    public $statusFilter = '';
    public $regionFilter = '';
    public $rejection_reason = '';
    public $confirmAction = '';
    public $confirmLenderId = null;
    public $currentPassword = '';
    public $passwordConfirmTitle = '';
    public $passwordConfirmMessage = '';

    // Fixed: Match database enum values exactly
    public $lender_status = [
        'pending',
        'approved', 
        'rejected',
        'suspended'
    ];

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'regionFilter' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        
    }

    public function passwordConfirmationRules()
    {
        return [
            'currentPassword' => 'required|string|min:1',
        ];
    }

    public function render()
    {
        $lenders = Lender::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%')
                      ->orWhere('region', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) { // Fixed: check for empty string
                $query->where('status', $this->statusFilter);
            })
            ->when($this->regionFilter !== '', function ($query) { // Fixed: check for empty string
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

        try {
            DB::transaction(function () {
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
                $lender = Lender::create([
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

                Log::info('Lender application created', [
                    'lender_id' => $lender->id,
                    'company_name' => $lender->company_name,
                    'created_by' => auth()->id()
                ]);
            });

            $this->hideAddLenderForm();
            session()->flash('message', 'Lender application submitted successfully!');
            $this->resetPage();

        } catch (\Exception $e) {
            Log::error('Failed to create lender application', [
                'error' => $e->getMessage(),
                'data' => [
                    'company_name' => $this->company_name,
                    'email' => $this->email
                ]
            ]);
            
            session()->flash('error', 'Failed to submit lender application. Please try again.');
        }
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

    public function closePasswordConfirmModal()
    {
        $this->showPasswordConfirmModal = false;
        $this->confirmAction = '';
        $this->confirmLenderId = null;
        $this->currentPassword = '';
        $this->passwordConfirmTitle = '';
        $this->passwordConfirmMessage = '';
        $this->resetValidation(['currentPassword']);
    }

    // New method to redirect to lender dashboard
    public function viewLenderDashboard($id)
    {
        return redirect()->route('lender.dashboard', ['lender' => $id]);
    }

    public function approveLender($id)
    {
        $lender = Lender::findOrFail($id);
        
        if ($lender->isPending()) {
            try {
                DB::transaction(function () use ($lender) {
                    // Create user account
                    $user = $lender->createUserAccount(auth()->id());
                    
                    Log::info('Lender approved and user account created', [
                        'lender_id' => $lender->id,
                        'user_id' => $user->id,
                        'approved_by' => auth()->id()
                    ]);
                });
                
                session()->flash('message', 'Lender approved successfully! User account created.');
                
            } catch (\Exception $e) {
                Log::error('Failed to approve lender', [
                    'lender_id' => $id,
                    'error' => $e->getMessage(),
                    'approved_by' => auth()->id()
                ]);
                
                session()->flash('error', 'Failed to approve lender. Please try again.');
            }
        }
    }

    public function rejectLender($id)
    {
        if (empty($this->rejection_reason)) {
            session()->flash('error', 'Please provide a rejection reason.');
            return;
        }

        try {
            $lender = Lender::findOrFail($id);
            
            if ($lender->isPending()) {
                $lender->update([
                    'status' => 'rejected',
                    'rejection_reason' => $this->rejection_reason
                ]);
                
                Log::info('Lender application rejected', [
                    'lender_id' => $id,
                    'rejection_reason' => $this->rejection_reason,
                    'rejected_by' => auth()->id()
                ]);
                
                $this->rejection_reason = '';
                session()->flash('message', 'Lender application rejected.');
            }

        } catch (\Exception $e) {
            Log::error('Failed to reject lender', [
                'lender_id' => $id,
                'error' => $e->getMessage(),
                'rejected_by' => auth()->id()
            ]);
            
            session()->flash('error', 'Failed to reject lender. Please try again.');
        }
    }

    // Critical actions that require password confirmation
    public function confirmSuspendLender($id)
    {
        $lender = Lender::findOrFail($id);
        
        if (!$lender->isApproved()) {
            session()->flash('error', 'Only approved lenders can be suspended.');
            return;
        }

        $this->confirmLenderId = $id;
        $this->confirmAction = 'suspendLender';
        $this->passwordConfirmTitle = 'Confirm Lender Suspension';
        $this->passwordConfirmMessage = 'Are you sure you want to suspend this lender? This will also deactivate their user account and is a critical action.';
        $this->showPasswordConfirmModal = true;
        $this->currentPassword = '';
        $this->resetValidation(['currentPassword']);
    }

    public function confirmDeleteLender($id)
    {
        $this->confirmLenderId = $id;
        $this->confirmAction = 'deleteLender';
        $this->passwordConfirmTitle = 'Confirm Lender Deletion';
        $this->passwordConfirmMessage = 'Are you sure you want to permanently delete this lender? This action will delete all associated data and cannot be undone. This is an extremely critical action.';
        $this->showPasswordConfirmModal = true;
        $this->currentPassword = '';
        $this->resetValidation(['currentPassword']);
    }

    public function executeConfirmedAction()
    {
        try {
            $this->validate($this->passwordConfirmationRules());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return;
        }

        // Verify current user's password
        if (!Hash::check($this->currentPassword, auth()->user()->password)) {
            $this->addError('currentPassword', 'The password is incorrect.');
            return;
        }

        try {
            if ($this->confirmAction === 'suspendLender') {
                $this->performSuspendLender($this->confirmLenderId);
            } elseif ($this->confirmAction === 'deleteLender') {
                $this->performDeleteLender($this->confirmLenderId);
            }

            $this->closePasswordConfirmModal();
            
        } catch (\Exception $e) {
            Log::error('Failed to execute confirmed action', [
                'action' => $this->confirmAction,
                'lender_id' => $this->confirmLenderId,
                'error' => $e->getMessage(),
                'executed_by' => auth()->id()
            ]);
            
            session()->flash('error', 'Failed to execute action. Please try again.');
        }
    }

    private function performSuspendLender($id)
    {
        $lender = Lender::findOrFail($id);
        
        if ($lender->isApproved()) {
            $lender->update(['status' => 'suspended']);
            
            // Suspend the user account too
            if ($lender->user) {
                $lender->user->update(['is_active' => false]);
            }
            
            Log::info('Lender suspended with password confirmation', [
                'lender_id' => $id,
                'suspended_by' => auth()->id()
            ]);
            
            session()->flash('message', 'Lender suspended successfully.');
        }
    }

    private function performDeleteLender($id)
    {
        $lender = Lender::findOrFail($id);
        
        DB::transaction(function () use ($lender) {
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
            
            Log::info('Lender deleted with password confirmation', [
                'deleted_lender_id' => $lender->id,
                'deleted_lender_name' => $lender->company_name,
                'deleted_by' => auth()->id()
            ]);
        });
        
        session()->flash('message', 'Lender deleted successfully.');
    }

    public function suspendLender($id)
    {
        // This method now calls password confirmation
        $this->confirmSuspendLender($id);
    }

    public function reactivateLender($id)
    {
        try {
            $lender = Lender::findOrFail($id);
            
            if ($lender->isSuspended()) {
                $lender->update(['status' => 'approved']);
                
                // Reactivate user account
                if ($lender->user) {
                    $lender->user->update(['is_active' => true]);
                }
                
                Log::info('Lender reactivated', [
                    'lender_id' => $id,
                    'reactivated_by' => auth()->id()
                ]);
                
                session()->flash('message', 'Lender reactivated successfully.');
            }

        } catch (\Exception $e) {
            Log::error('Failed to reactivate lender', [
                'lender_id' => $id,
                'error' => $e->getMessage(),
                'reactivated_by' => auth()->id()
            ]);
            
            session()->flash('error', 'Failed to reactivate lender. Please try again.');
        }
    }

    public function deleteLender($id)
    {
        // This method now calls password confirmation
        $this->confirmDeleteLender($id);
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
            'pending' => Lender::where('status', 'pending')->count(),
            'approved' => Lender::where('status', 'approved')->count(),
            'rejected' => Lender::where('status', 'rejected')->count(),
            'suspended' => Lender::where('status', 'suspended')->count(),
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
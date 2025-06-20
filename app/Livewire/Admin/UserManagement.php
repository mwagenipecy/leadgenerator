<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Lender;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    public $lenderFilter = '';
    public $showCreateUserModal = false;
    public $showEditUserModal = false;
    public $showCreateLenderModal = false;
    public $selectedUser = null;

    // Create User Properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'user';
    public $first_name = '';
    public $last_name = '';
    public $phone = '';
    public $nida_number = '';
    public $date_of_birth = '';
    public $is_active = true;
    public $selected_lender_id = '';

    // Create Lender Properties
    public $company_name = '';
    public $license_number = '';
    public $contact_person = '';
    public $lender_email = '';
    public $lender_phone = '';
    public $address = '';
    public $city = '';
    public $region = '';
    public $postal_code = '';
    public $website = '';
    public $description = '';
    public $lender_password = '';
    public $lender_password_confirmation = '';

    // Edit User Properties
    public $edit_name = '';
    public $edit_email = '';
    public $edit_role = '';
    public $edit_first_name = '';
    public $edit_last_name = '';
    public $edit_phone = '';
    public $edit_nida_number = '';
    public $edit_date_of_birth = '';
    public $edit_is_active = true;
    public $edit_selected_lender_id = '';

    public $totalUsers;
    public $totalLenders;
    public $totalBorrowers;
    public $totalAdmins;
    public $recentUsers;
    public $availableLenders;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->loadStats();
        $this->availableLenders = Lender::where('status', 'approved')->get();
    }

    public function loadStats()
    {
        $this->totalUsers = User::count();
        $this->totalLenders = User::where('role', 'lender')->count();
        $this->totalBorrowers = User::where('role', 'user')->count();
        $this->totalAdmins = User::where('role', 'admin')->count();
        $this->recentUsers = User::with('lender')->latest()->limit(5)->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingLenderFilter()
    {
        $this->resetPage();
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,lender,user',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'nida_number' => 'nullable|string|max:255|unique:users,nida_number',
            'date_of_birth' => 'nullable|date|before:today',
            'is_active' => 'boolean',
            'selected_lender_id' => 'nullable|exists:lenders,id',
        ];

        return $rules;
    }

    public function editRules()
    {
        return [
            'edit_name' => 'required|string|max:255',
            'edit_email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->selectedUser?->id)],
            'edit_role' => 'required|in:admin,lender,user',
            'edit_first_name' => 'nullable|string|max:255',
            'edit_last_name' => 'nullable|string|max:255',
            'edit_phone' => 'nullable|string|max:255',
            'edit_nida_number' => ['nullable', 'string', 'max:255', Rule::unique('users', 'nida_number')->ignore($this->selectedUser?->id)],
            'edit_date_of_birth' => 'nullable|date|before:today',
            'edit_is_active' => 'boolean',
            'edit_selected_lender_id' => 'nullable|exists:lenders,id',
        ];
    }

    public function lenderRules()
    {
        return [
            'company_name' => 'required|string|max:255',
            'license_number' => 'nullable|string|max:255|unique:lenders,license_number',
            'contact_person' => 'required|string|max:255',
            'lender_email' => 'required|email|unique:users,email',
            'lender_phone' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'lender_password' => 'required|string|min:8|confirmed',
        ];
    }

    public function openCreateUserModal()
    {
        $this->resetValidation();
        $this->resetCreateUserForm();
        $this->showCreateUserModal = true;
    }

    public function openCreateLenderModal()
    {
        $this->resetValidation();
        $this->resetCreateLenderForm();
        $this->showCreateLenderModal = true;
    }

    public function openEditUserModal($userId)
    {
        $this->selectedUser = User::with('lender')->find($userId);
        $this->resetValidation();
        
        $this->edit_name = $this->selectedUser->name;
        $this->edit_email = $this->selectedUser->email;
        $this->edit_role = $this->selectedUser->role;
        $this->edit_first_name = $this->selectedUser->first_name;
        $this->edit_last_name = $this->selectedUser->last_name;
        $this->edit_phone = $this->selectedUser->phone;
        $this->edit_nida_number = $this->selectedUser->nida_number;
        $this->edit_date_of_birth = $this->selectedUser->date_of_birth?->format('Y-m-d');
        $this->edit_is_active = $this->selectedUser->is_active;
        $this->edit_selected_lender_id = $this->selectedUser->lender_id ?? '';
        
        $this->showEditUserModal = true;
    }

    public function createUser()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'nida_number' => $this->nida_number,
            'date_of_birth' => $this->date_of_birth,
            'is_active' => $this->is_active,
            'email_verified_at' => now(), // Auto-verify admin created users
        ];

        // Add lender association if selected and role is appropriate
        if ($this->selected_lender_id && in_array($this->role, ['lender', 'user'])) {
            $userData['lender_id'] = $this->selected_lender_id;
        }

        User::create($userData);

        $this->loadStats();
        $this->resetCreateUserForm();
        $this->showCreateUserModal = false;
        session()->flash('message', 'User created successfully!');
    }

    public function updateUser()
    {
        $this->validate($this->editRules());

        $userData = [
            'name' => $this->edit_name,
            'email' => $this->edit_email,
            'role' => $this->edit_role,
            'first_name' => $this->edit_first_name,
            'last_name' => $this->edit_last_name,
            'phone' => $this->edit_phone,
            'nida_number' => $this->edit_nida_number,
            'date_of_birth' => $this->edit_date_of_birth,
            'is_active' => $this->edit_is_active,
        ];

        // Handle lender association
        if ($this->edit_selected_lender_id && in_array($this->edit_role, ['lender', 'user'])) {
            $userData['lender_id'] = $this->edit_selected_lender_id;
        } else {
            $userData['lender_id'] = null;
        }

        $this->selectedUser->update($userData);

        $this->loadStats();
        $this->showEditUserModal = false;
        session()->flash('message', 'User updated successfully!');
    }

    public function createLender()
    {
        $this->validate($this->lenderRules());

        // Create user account for lender
        $user = User::create([
            'name' => $this->contact_person,
            'email' => $this->lender_email,
            'password' => Hash::make($this->lender_password),
            'role' => 'lender',
            'first_name' => explode(' ', $this->contact_person)[0] ?? '',
            'last_name' => explode(' ', $this->contact_person)[1] ?? '',
            'phone' => $this->lender_phone,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create lender record
        $lender = Lender::create([
            'company_name' => $this->company_name,
            'license_number' => $this->license_number,
            'contact_person' => $this->contact_person,
            'email' => $this->lender_email,
            'phone' => $this->lender_phone,
            'address' => $this->address,
            'city' => $this->city,
            'region' => $this->region,
            'postal_code' => $this->postal_code,
            'website' => $this->website,
            'description' => $this->description,
            'status' => 'approved', // Auto-approve admin created lenders
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'user_id' => $user->id,
        ]);

        // Update user with lender_id
        $user->update(['lender_id' => $lender->id]);

        $this->availableLenders = Lender::where('status', 'approved')->get();
        $this->loadStats();
        $this->resetCreateLenderForm();
        $this->showCreateLenderModal = false;
        session()->flash('message', 'Lender created successfully!');
    }

    public function toggleUserStatus($userId)
    {
        $user = User::find($userId);
        $user->update(['is_active' => !$user->is_active]);
        
        $this->loadStats();
        session()->flash('message', 'User status updated successfully!');
    }

    public function deleteUser($userId)
    {
        if ($userId === Auth::id()) {
            session()->flash('error', 'You cannot delete your own account!');
            return;
        }

        $user = User::find($userId);
        
        // If user is a lender, also handle lender record
        if ($user->role === 'lender' && $user->lender) {
            $user->lender->delete();
        }
        
        $user->delete();
        
        $this->loadStats();
        session()->flash('message', 'User deleted successfully!');
    }

    public function resetCreateUserForm()
    {
        $this->reset([
            'name', 'email', 'password', 'password_confirmation', 'role',
            'first_name', 'last_name', 'phone', 'nida_number', 'date_of_birth',
            'is_active', 'selected_lender_id'
        ]);
        $this->role = 'user';
        $this->is_active = true;
    }

    public function resetCreateLenderForm()
    {
        $this->reset([
            'company_name', 'license_number', 'contact_person', 'lender_email',
            'lender_phone', 'address', 'city', 'region', 'postal_code',
            'website', 'description', 'lender_password', 'lender_password_confirmation'
        ]);
    }

    public function render()
    {
        $query = User::with('lender')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%')
                          ->orWhere('first_name', 'like', '%' . $this->search . '%')
                          ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function ($q) {
                $q->where('role', $this->roleFilter);
            })
            ->when($this->statusFilter, function ($q) {
                if ($this->statusFilter === 'active') {
                    $q->where('is_active', true);
                } elseif ($this->statusFilter === 'inactive') {
                    $q->where('is_active', false);
                }
            })
            ->when($this->lenderFilter, function ($q) {
                $q->where('lender_id', $this->lenderFilter);
            });

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}
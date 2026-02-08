<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Lender;
use App\Mail\UserStatusChangeNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Models\Permission;
use App\Services\LogService; 

class UserManagement extends Component
{
    use WithPagination;

    public $activeTab = 'users'; // 'users', 'roles', 'permissions'
    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    public $lenderFilter = '';
    public $showCreateUserModal = false;
    public $showEditUserModal = false;
    public $showCreateLenderModal = false;
    public $showPasswordConfirmModal = false;
    public $selectedUser = null;
    public $confirmAction = '';
    public $confirmUserId = null;
    public $currentPassword = '';
    public $passwordConfirmTitle = '';
    public $passwordConfirmMessage = '';

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
    public $roles = [];

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'lenderFilter' => ['except' => ''],
    ];

    protected $listeners = [
        'confirmToggleStatus' => 'confirmToggleStatus'
    ];

    public function mount()
    {
        $this->loadStats();
        $this->roles = Role::get();
        $this->availableLenders = Lender::where('status', 'approved')->get();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage(); // Reset pagination when switching tabs
    }

    public function passwordConfirmationRules()
    {
        return [
            'currentPassword' => 'required|string|min:1',
        ];
    }

    public function loadStats()
    {
        $this->totalUsers = User::count();
        $this->totalLenders = User::where('role', 'lender')->count();
        $this->totalBorrowers = User::where('role', 'borrower')->count();
        $this->totalAdmins = User::where('role', 'Super_admin')->count();
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

    public function updatingStatusFilter()
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

    public function openCreateUserModal()
    {
        $this->resetValidation();
        $this->resetCreateUserForm();
        $this->showCreateUserModal = true;
    }

    public function closeCreateUserModal()
    {
        $this->showCreateUserModal = false;
        $this->resetValidation();
        $this->resetCreateUserForm();
    }

    public function openEditUserModal($userId)
    {
        $this->selectedUser = User::with('lender')->find($userId);
        
        if (!$this->selectedUser) {
            session()->flash('error', 'User not found.');
            return;
        }

        $this->resetValidation();
        
        $this->edit_name = $this->selectedUser->name;
        $this->edit_email = $this->selectedUser->email;
        $this->edit_role = $this->selectedUser->role;
        $this->edit_first_name = $this->selectedUser->first_name;
        $this->edit_last_name = $this->selectedUser->last_name;
        $this->edit_phone = $this->selectedUser->phone;
        $this->edit_nida_number = $this->selectedUser->nida_number;
        $this->edit_date_of_birth = $this->selectedUser->date_of_birth
        ? (new \DateTime($this->selectedUser->date_of_birth))->format('Y-m-d')
        : null;
        
        $this->edit_is_active = $this->selectedUser->is_active;
        $this->edit_selected_lender_id = $this->selectedUser->lender_id ?? '';
        
        $this->showEditUserModal = true;
    }

    public function closeEditUserModal()
    {
        $this->showEditUserModal = false;
        $this->selectedUser = null;
        $this->resetValidation();
        $this->resetEditUserForm();
    }

    public function createUser()
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors will be automatically displayed
            return;
        }

        try {
            DB::transaction(function () {
                // Create user data
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
                    'email_verified_at' => now(),
                ];

                // Add lender association if selected and role is appropriate
                if ($this->selected_lender_id && in_array($this->role, ['lender', 'user'])) {
                    $userData['lender_id'] = $this->selected_lender_id;
                }

                // Create the user
                $user = User::create($userData);

                // Assign role using the new role system
                $this->assignRoleToUser($user, $this->role);

                // Log user creation
                LogService::logUserCreated($user, ['created_by' => auth()->id()]);

                Log::info('User created successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $this->role,
                    'created_by' => auth()->id()
                ]);
            });

            $this->loadStats();
            $this->closeCreateUserModal();
            
            session()->flash('message', 'User created successfully!');
            
        } catch (\Exception $e) {
            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_data' => [
                    'email' => $this->email,
                    'role' => $this->role
                ]
            ]);
            
            session()->flash('error', 'Failed to create user. Please try again.');
        }
    }

    public function updateUser()
    {
        try {
            $this->validate($this->editRules());
        } catch (\Illuminate\Validation\ValidationException $e) {
            return;
        }

        try {
            DB::transaction(function () {
                // Get old values before update
                $oldValues = $this->selectedUser->toArray();
                
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

                // Update user data
                $this->selectedUser->update($userData);
                
                // Get new values after update
                $newValues = array_intersect_key($this->selectedUser->fresh()->toArray(), $userData);

                // Update role assignment if role changed
                if ($this->selectedUser->role !== $this->edit_role) {
                    $this->updateUserRole($this->selectedUser, $this->edit_role);
                }

                // Log user update
                LogService::logUserUpdated($this->selectedUser, $oldValues, $newValues);

                Log::info('User updated successfully', [
                    'user_id' => $this->selectedUser->id,
                    'updated_by' => auth()->id(),
                    'changes' => $userData
                ]);
            });

            $this->loadStats();
            $this->closeEditUserModal();
            
            session()->flash('message', 'User updated successfully!');
            
        } catch (\Exception $e) {
            Log::error('User update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $this->selectedUser?->id
            ]);
            
            session()->flash('error', 'Failed to update user. Please try again.');
        }
    }

    private function assignRoleToUser($user, $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            throw new \Exception("Role '{$roleName}' not found.");
        }

        if (method_exists($user, 'assignRole')) {
            $user->assignRole($role, auth()->user());
        } else {
            $user->roles()->syncWithoutDetaching([
                $role->id => [
                    'assigned_at' => now(),
                    'assigned_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
            
            $user->update(['role_level' => $role->level ?? 1]);
        }

        // Log role assignment
        LogService::logRoleAssigned($user, $roleName, auth()->user());

        Log::info('Role assigned to user', [
            'user_id' => $user->id,
            'role' => $roleName,
            'assigned_by' => auth()->id()
        ]);
    }

    private function updateUserRole($user, $newRoleName)
    {
        $newRole = Role::where('name', $newRoleName)->first();
        
        if (!$newRole) {
            throw new \Exception("Role '{$newRoleName}' not found.");
        }

        $oldRoleName = $user->role;
        $user->roles()->detach();
        
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($newRole, auth()->user());
        } else {
            $user->roles()->attach($newRole->id, [
                'assigned_at' => now(),
                'assigned_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $user->update(['role_level' => $newRole->level ?? 1]);
        }

        // Log role change
        LogService::logRoleAssigned($user, $newRoleName, auth()->user());

        Log::info('User role updated', [
            'user_id' => $user->id,
            'old_role' => $oldRoleName,
            'new_role' => $newRoleName,
            'updated_by' => auth()->id()
        ]);
    }

    public function confirmToggleStatus($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        if ($userId === Auth::id()) {
            session()->flash('error', 'You cannot change your own status!');
            return;
        }

        $this->confirmUserId = $userId;
        $this->confirmAction = 'toggleStatus';
        $this->passwordConfirmTitle = 'Confirm Status Change';
        $action = $user->is_active ? 'disable' : 'enable';
        $this->passwordConfirmMessage = "Are you sure you want to {$action} this user? This is a critical action that will " . ($user->is_active ? 'prevent the user from accessing the system' : 'restore the user\'s access to the system') . ". An email notification will be sent to the user.";
        
        // Reset password and validation
        $this->currentPassword = '';
        $this->resetValidation(['currentPassword']);
        
        // Set modal to show - this must be last to ensure all properties are set
        $this->showPasswordConfirmModal = true;
        
        Log::info('Password confirmation modal opened', [
            'user_id' => $userId,
            'action' => $action,
            'opened_by' => auth()->id()
        ]);
    }


    public function closePasswordConfirmModal()
    {
        $this->showPasswordConfirmModal = false;
        $this->confirmAction = '';
        $this->confirmUserId = null;
        $this->currentPassword = '';
        $this->passwordConfirmTitle = '';
        $this->passwordConfirmMessage = '';
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
            if ($this->confirmAction === 'toggleStatus') {
                $this->performToggleUserStatus($this->confirmUserId);
            }

            $this->closePasswordConfirmModal();
            
        } catch (\Exception $e) {
            Log::error('Failed to execute confirmed action', [
                'action' => $this->confirmAction,
                'user_id' => $this->confirmUserId,
                'error' => $e->getMessage(),
                'executed_by' => auth()->id()
            ]);
            
            session()->flash('error', 'Failed to execute action. Please try again.');
        }
    }

    private function performToggleUserStatus($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            session()->flash('error', 'User not found.');
            return;
        }

        $oldStatus = $user->is_active;
        $newStatus = !$user->is_active;
        $isDisabled = !$newStatus;
        
        $user->update(['is_active' => $newStatus]);
        
        // Send email notification to the user
        try {
            Mail::to($user->email)->send(
                new UserStatusChangeNotification($user, $isDisabled)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send user status change email', [
                'user_id' => $userId,
                'user_email' => $user->email,
                'is_disabled' => $isDisabled,
                'error' => $e->getMessage()
            ]);
        }
        
        // Log status change
        LogService::logUserStatusChanged($user, $newStatus);
        
        $this->loadStats();
        
        $status = $newStatus ? 'enabled' : 'disabled';
        $message = "User {$status} successfully!";
        if ($isDisabled) {
            $message .= " An email notification has been sent to the user.";
        }
        session()->flash('message', $message);
        
        Log::info('User status changed with password confirmation', [
            'user_id' => $userId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'is_disabled' => $isDisabled,
            'changed_by' => auth()->id()
        ]);
    }

    public function toggleUserStatus($userId)
    {
        try {
            // This method is now called through password confirmation
            $this->confirmToggleStatus($userId);
        } catch (\Exception $e) {
            Log::error('Error in toggleUserStatus', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'An error occurred. Please try again.');
        }
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

    public function resetEditUserForm()
    {
        $this->reset([
            'edit_name', 'edit_email', 'edit_role', 'edit_first_name', 
            'edit_last_name', 'edit_phone', 'edit_nida_number', 
            'edit_date_of_birth', 'edit_is_active', 'edit_selected_lender_id'
        ]);
    }

    public function render()
    {
        // Reload stats on each render to ensure they're always available
        $this->loadStats();
        
        $query = User::query();

        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        if ($this->statusFilter) {
            if ($this->statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->statusFilter === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($this->lenderFilter) {
            $query->where('lender_id', $this->lenderFilter);
        }

        $users = $query->with('lender')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}
<?php

// app/Livewire/Admin/RoleManagement.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RoleManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $statusFilter = '';
    public $levelFilter = '';

    // Modal states
    public $showCreateRoleModal = false;
    public $showEditRoleModal = false;
    public $showDeleteRoleModal = false;
    public $showManagePermissionsModal = false;
    public $showRoleUsersModal = false;
    public $showPasswordConfirmModal = false;

    // Selected items
    public $selectedRole = null;
    public $roleToDelete = null;
    public $confirmAction = '';
    public $confirmRoleId = null;
    public $currentPassword = '';
    public $passwordConfirmTitle = '';
    public $passwordConfirmMessage = '';

    // Create role properties
    public $name = '';
    public $display_name = '';
    public $description = '';
    public $level = 1;
    public $is_active = true;

    // Edit role properties
    public $edit_name = '';
    public $edit_display_name = '';
    public $edit_description = '';
    public $edit_level = 1;
    public $edit_is_active = true;

    // Permission management
    public $selectedPermissions = [];
    // Don't store as Livewire properties to avoid serialization issues
    // Load them in render() or methods when needed

    // Role users - store as array to avoid serialization issues
    public $roleUsers = [];

    // Stats
    public $totalRoles;
    public $totalCustomRoles;
    public $totalSystemRoles;
    public $totalActiveRoles;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->checkPermissions();
        $this->loadStats();
        // Don't load permissions here - load them in render() to avoid serialization issues
    }

    protected function checkPermissions()
    {
        // if (!auth()->user()->hasPermission('roles.view')) {
        //     abort(403, 'You do not have permission to view roles.');
        // }
    }

    public function loadStats()
    {
        $this->totalRoles = Role::count();
        $this->totalCustomRoles = Role::where('is_system_role', false)->count();
        $this->totalSystemRoles = Role::where('is_system_role', true)->count();
        $this->totalActiveRoles = Role::where('is_active', true)->count();
    }

    // Helper method to get permissions (not stored as property)
    protected function getPermissionsByCategory()
    {
        return Permission::where('is_active', true)
            ->orderBy('category')
            ->orderBy('display_name')
            ->get()
            ->groupBy('category');
    }

    // Create Role Methods
    public function openCreateRoleModal()
    {
        if (!auth()->user()->hasPermission('roles.create')) {
            session()->flash('error', 'You do not have permission to create roles.');
            return;
        }

        $this->resetValidation();
        $this->resetCreateRoleForm();
        $this->showCreateRoleModal = true;
    }

    public function createRole()
    {
        if (!auth()->user()->hasPermission('roles.create')) {
            session()->flash('error', 'You do not have permission to create roles.');
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'level' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'Role name must contain only lowercase letters and underscores.',
        ]);

        // Check if current user can create role with this level
        if (auth()->user()->role_level <= $this->level) {
            session()->flash('error', 'You cannot create a role with level equal or higher than your own.');
            return;
        }

        $role = Role::create([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'level' => $this->level,
            'is_active' => $this->is_active,
            'is_system_role' => false,
        ]);

        // Log role creation
        LogService::logRoleCreated($role);

        $this->loadStats();
        $this->resetCreateRoleForm();
        $this->showCreateRoleModal = false;
        session()->flash('message', 'Role created successfully!');
    }

    // Edit Role Methods
    public function openEditRoleModal($roleId)
    {
        if (!auth()->user()->hasPermission('roles.edit')) {
            session()->flash('error', 'You do not have permission to edit roles.');
            return;
        }

        $this->selectedRole = Role::findOrFail($roleId);

        // Check if user can edit this role
        if (auth()->user()->role_level <= $this->selectedRole->level) {
            session()->flash('error', 'You cannot edit a role with level equal or higher than your own.');
            return;
        }

        $this->resetValidation();
        $this->edit_name = $this->selectedRole->name;
        $this->edit_display_name = $this->selectedRole->display_name;
        $this->edit_description = $this->selectedRole->description;
        $this->edit_level = $this->selectedRole->level;
        $this->edit_is_active = $this->selectedRole->is_active;

        $this->showEditRoleModal = true;
    }

    public function updateRole()
    {
        if (!auth()->user()->hasPermission('roles.edit')) {
            session()->flash('error', 'You do not have permission to edit roles.');
            return;
        }

        $this->validate([
            'edit_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->selectedRole->id),
                'regex:/^[a-z_]+$/'
            ],
            'edit_display_name' => 'required|string|max:255',
            'edit_description' => 'nullable|string|max:1000',
            'edit_level' => 'required|integer|min:1|max:100',
            'edit_is_active' => 'boolean',
        ]);

        // Check permissions for level changes
        if (auth()->user()->role_level <= $this->edit_level) {
            session()->flash('error', 'You cannot set a role level equal or higher than your own.');
            return;
        }

        // Store old values for logging
        $oldValues = [
            'display_name' => $this->selectedRole->display_name,
            'description' => $this->selectedRole->description,
            'level' => $this->selectedRole->level,
            'is_active' => $this->selectedRole->is_active,
        ];
        
        if (!$this->selectedRole->is_system_role) {
            $oldValues['name'] = $this->selectedRole->name;
        }

        $updateData = [
            'display_name' => $this->edit_display_name,
            'description' => $this->edit_description,
            'level' => $this->edit_level,
            'is_active' => $this->edit_is_active,
        ];

        // Only allow name change for non-system roles
        if (!$this->selectedRole->is_system_role) {
            $updateData['name'] = $this->edit_name;
        }

        $this->selectedRole->update($updateData);

        // Log role update
        LogService::logRoleUpdated($this->selectedRole, $oldValues, $updateData);

        // Update role levels for users with this role
        // Get user IDs first to avoid loading full user models
        $userIds = $this->selectedRole->users()->pluck('id');
        User::whereIn('id', $userIds)->get()->each(function ($user) {
            $user->updateRoleLevel();
        });

        $this->loadStats();
        $this->showEditRoleModal = false;
        session()->flash('message', 'Role updated successfully!');
    }

    // Delete Role Methods
    public function confirmDeleteRole($roleId)
    {
        if (!auth()->user()->hasPermission('roles.delete')) {
            session()->flash('error', 'You do not have permission to delete roles.');
            return;
        }

        $this->roleToDelete = Role::findOrFail($roleId);

        if ($this->roleToDelete->is_system_role) {
            session()->flash('error', 'Cannot delete system roles.');
            return;
        }

        if (auth()->user()->role_level <= $this->roleToDelete->level) {
            session()->flash('error', 'You cannot delete a role with level equal or higher than your own.');
            return;
        }

        $this->showDeleteRoleModal = true;
    }

    public function deleteRole()
    {
        if (!$this->roleToDelete || !auth()->user()->hasPermission('roles.delete')) {
            session()->flash('error', 'You do not have permission to delete roles.');
            return;
        }

        if ($this->roleToDelete->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role that has assigned users. Please reassign users first.');
            return;
        }

        $roleName = $this->roleToDelete->display_name;
        $this->roleToDelete->delete();

        $this->loadStats();
        $this->showDeleteRoleModal = false;
        $this->roleToDelete = null;
        session()->flash('message', "Role '{$roleName}' deleted successfully!");
    }

    // Permission Management Methods
    public function openManagePermissionsModal($roleId)
    {
        if (!auth()->user()->hasPermission('roles.edit')) {
            session()->flash('error', 'You do not have permission to manage role permissions.');
            return;
        }

        // Load role - don't eager load relationships to avoid serialization issues
        $this->selectedRole = Role::findOrFail($roleId);

        if (auth()->user()->role_level <= $this->selectedRole->level) {
            session()->flash('error', 'You cannot manage permissions for a role with level equal or higher than your own.');
            return;
        }

        // Get permission IDs without loading the full relationship
        $this->selectedPermissions = $this->selectedRole->permissions()->pluck('permissions.id')->toArray();
        $this->showManagePermissionsModal = true;
    }

    public function updateRolePermissions()
    {
        if (!auth()->user()->hasPermission('roles.edit') || !$this->selectedRole) {
            session()->flash('error', 'You do not have permission to manage role permissions.');
            return;
        }

        DB::transaction(function () {
            $this->selectedRole->permissions()->sync($this->selectedPermissions);

            // Clear permissions cache for all users with this role
            // Use direct update on user_ids to avoid loading user models
            $userIds = $this->selectedRole->users()->pluck('id');
            if ($userIds->isNotEmpty()) {
                User::whereIn('id', $userIds)->update([
                    'permissions_cache' => null,
                    'permissions_updated_at' => null
                ]);
            }
        });

        $this->showManagePermissionsModal = false;
        session()->flash('message', 'Role permissions updated successfully!');
    }

    // Role Users Methods
    public function openRoleUsersModal($roleId)
    {
        if (!auth()->user()->hasPermission('users.view')) {
            session()->flash('error', 'You do not have permission to view users.');
            return;
        }

        // Load role without nested relationships to avoid serialization issues
        $this->selectedRole = Role::findOrFail($roleId);
        
        // Load users and convert to array to avoid Collection serialization issues
        $users = $this->selectedRole->users()->get();
        $this->roleUsers = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name ?? $user->email,
                'email' => $user->email,
                'role' => $user->role,
            ];
        })->toArray();

        $this->showRoleUsersModal = true;
    }

    public function removeUserFromRole($userId)
    {
        if (!auth()->user()->hasPermission('roles.assign')) {
            session()->flash('error', 'You do not have permission to manage user roles.');
            return;
        }

        $user = User::findOrFail($userId);

        if (!auth()->user()->canManage($user)) {
            session()->flash('error', 'You cannot manage this user.');
            return;
        }

        $user->removeRole($this->selectedRole);
        
        // Refresh the role users list - convert to array to avoid serialization issues
        $this->selectedRole->refresh();
        $users = $this->selectedRole->users()->get();
        $this->roleUsers = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name ?? $user->email,
                'email' => $user->email,
                'role' => $user->role,
            ];
        })->toArray();

        session()->flash('message', 'User removed from role successfully!');
    }

    // Confirm toggle role status
    public function confirmToggleRoleStatus($roleId)
    {
        if (!auth()->user()->hasPermission('roles.edit')) {
            session()->flash('error', 'You do not have permission to edit roles.');
            return;
        }

        $role = Role::findOrFail($roleId);

        if (auth()->user()->role_level <= $role->level) {
            session()->flash('error', 'You cannot modify a role with level equal or higher than your own.');
            return;
        }

        $this->confirmRoleId = $roleId;
        $this->confirmAction = 'toggleStatus';
        $this->passwordConfirmTitle = 'Confirm Status Change';
        $action = $role->is_active ? 'deactivate' : 'activate';
        $this->passwordConfirmMessage = "Are you sure you want to {$action} the role '{$role->display_name}'? This is a critical action that will " . ($role->is_active ? 'prevent users with this role from accessing certain features' : 'restore access for users with this role') . ".";
        $this->showPasswordConfirmModal = true;
        $this->currentPassword = '';
        $this->resetValidation(['currentPassword']);
    }

    // Toggle role status (after confirmation)
    private function performToggleRoleStatus($roleId)
    {
        $role = Role::findOrFail($roleId);

        if (auth()->user()->role_level <= $role->level) {
            session()->flash('error', 'You cannot modify a role with level equal or higher than your own.');
            return;
        }

        $oldStatus = $role->is_active;
        $role->update(['is_active' => !$role->is_active]);
        
        // Log role status change
        LogService::logRoleStatusChanged($role, $role->is_active);
        
        $this->loadStats();
        
        $status = $role->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Role {$status} successfully!");
    }

    public function closePasswordConfirmModal()
    {
        $this->showPasswordConfirmModal = false;
        $this->confirmAction = '';
        $this->confirmRoleId = null;
        $this->currentPassword = '';
        $this->passwordConfirmTitle = '';
        $this->passwordConfirmMessage = '';
        $this->resetValidation(['currentPassword']);
    }

    public function passwordConfirmationRules()
    {
        return [
            'currentPassword' => 'required|string',
        ];
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
                $this->performToggleRoleStatus($this->confirmRoleId);
            }

            $this->closePasswordConfirmModal();
            
        } catch (\Exception $e) {
            \Log::error('Failed to execute confirmed action', [
                'action' => $this->confirmAction,
                'role_id' => $this->confirmRoleId,
                'error' => $e->getMessage(),
                'executed_by' => auth()->id()
            ]);
            
            session()->flash('error', 'Failed to execute action. Please try again.');
        }
    }

    // Reset methods
    public function resetCreateRoleForm()
    {
        $this->reset([
            'name', 'display_name', 'description', 'level', 'is_active'
        ]);
        $this->level = 1;
        $this->is_active = true;
    }

    // Search and filter updates
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingLevelFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        try {
            $query = Role::query()
                ->when($this->search, function ($q) {
                    $q->where('display_name', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->when($this->statusFilter, function ($q) {
                    if ($this->statusFilter === 'active') {
                        $q->where('is_active', true);
                    } elseif ($this->statusFilter === 'inactive') {
                        $q->where('is_active', false);
                    } elseif ($this->statusFilter === 'system') {
                        $q->where('is_system_role', true);
                    } elseif ($this->statusFilter === 'custom') {
                        $q->where('is_system_role', false);
                    }
                })
                ->when($this->levelFilter, function ($q) {
                    if ($this->levelFilter === 'high') {
                        $q->where('level', '>=', 80);
                    } elseif ($this->levelFilter === 'medium') {
                        $q->whereBetween('level', [50, 79]);
                    } elseif ($this->levelFilter === 'low') {
                        $q->where('level', '<', 50);
                    }
                });

            // Load counts only (avoid loading full relationships to prevent serialization issues)
            $roles = $query->withCount(['users', 'permissions'])
                ->orderBy('level', 'desc')
                ->paginate(12);

            // Load permissions only when rendering (not stored as property)
            $permissionsByCategory = $this->getPermissionsByCategory();

            return view('livewire.admin.role-management', [
                'roles' => $roles,
                'permissionsByCategory' => $permissionsByCategory,
            ]);
        } catch (\Exception $e) {
            \Log::error('RoleManagement render error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return empty permissions on error
            $permissionsByCategory = collect();
            
            return view('livewire.admin.role-management', [
                'roles' => Role::query()->paginate(12),
                'permissionsByCategory' => $permissionsByCategory,
            ]);
        }
    }
}
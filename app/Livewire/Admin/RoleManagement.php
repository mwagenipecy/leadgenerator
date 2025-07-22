<?php

// app/Livewire/Admin/RoleManagement.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    // Selected items
    public $selectedRole = null;
    public $roleToDelete = null;

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
    public $availablePermissions = [];
    public $permissionsByCategory = [];

    // Role users
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
        $this->loadPermissions();
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

    public function loadPermissions()
    {
        $this->availablePermissions = Permission::where('is_active', true)
            ->orderBy('category')
            ->orderBy('display_name')
            ->get();

        $this->permissionsByCategory = $this->availablePermissions->groupBy('category');
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

        Role::create([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'level' => $this->level,
            'is_active' => $this->is_active,
            'is_system_role' => false,
        ]);

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

        // Update role levels for users with this role
        $this->selectedRole->users()->each(function ($user) {
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

        $this->selectedRole = Role::with('permissions')->findOrFail($roleId);

        if (auth()->user()->role_level <= $this->selectedRole->level) {
            session()->flash('error', 'You cannot manage permissions for a role with level equal or higher than your own.');
            return;
        }

        $this->selectedPermissions = $this->selectedRole->permissions->pluck('id')->toArray();
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
            $this->selectedRole->users()->update([
                'permissions_cache' => null,
                'permissions_updated_at' => null
            ]);
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

        $this->selectedRole = Role::with(['users' => function ($query) {
            $query->with('lender');
        }])->findOrFail($roleId);

        $this->roleUsers = $this->selectedRole->users;
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
        
        // Refresh the role users list
        $this->selectedRole->load('users');
        $this->roleUsers = $this->selectedRole->users;

        session()->flash('message', 'User removed from role successfully!');
    }

    // Toggle role status
    public function toggleRoleStatus($roleId)
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

        $role->update(['is_active' => !$role->is_active]);
        $this->loadStats();
        
        $status = $role->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Role {$status} successfully!");
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
        $query = Role::with(['permissions', 'users'])
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

        // Filter based on user's role level - users can only see roles below their level
        // if (!auth()->user()->hasRole('super_admin')) {
        //     $query->where('level', '<', auth()->user()->role_level);
        // }

        $roles = $query->orderBy('level', 'desc')->paginate(12) ; //->paginate(12);

        return view('livewire.admin.role-management', [
            'roles' => $roles,
            'permissionsByCategory' => $this->permissionsByCategory,
        ]);
    }
}
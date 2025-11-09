<?php

// app/Livewire/Admin/PermissionManagement.php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PermissionManagement extends Component
{
    use WithPagination;

    // Search and filters
    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';

    // Modal states
    public $showCreatePermissionModal = false;
    public $showEditPermissionModal = false;
    public $showDeletePermissionModal = false;
    public $showPermissionRolesModal = false;

    // Selected items
    public $selectedPermission = null;
    public $permissionToDelete = null;

    // Create permission properties
    public $name = '';
    public $display_name = '';
    public $description = '';
    public $category = 'general';
    public $is_active = true;

    // Edit permission properties
    public $edit_name = '';
    public $edit_display_name = '';
    public $edit_description = '';
    public $edit_category = 'general';
    public $edit_is_active = true;

    // Permission roles - store as array to avoid serialization issues
    public $permissionRoles = [];

    // Stats
    public $totalPermissions;
    public $totalActivePermissions;
    public $totalCategories;
    // Don't store permissionsByCategory as property - load in render() to avoid serialization issues

    // Available categories
    public $categories = [
        'users' => 'User Management',
        'roles' => 'Role Management',
        'applications' => 'Application Management',
        'lenders' => 'Lender Management',
        'products' => 'Product Management',
        'reports' => 'Reports & Analytics',
        'system' => 'System Administration',
        'financial' => 'Financial Management',
        'general' => 'General'
    ];

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
       // $this->checkPermissions();
        $this->loadStats();
    }

    protected function checkPermissions()
    {
        if (!auth()->user()->hasPermission('roles.view')) {
            abort(403, 'You do not have permission to view permissions.');
        }
    }

    public function loadStats()
    {
        $this->totalPermissions = Permission::count();
        $this->totalActivePermissions = Permission::where('is_active', true)->count();
        $this->totalCategories = Permission::distinct('category')->count();
        // Don't load permissionsByCategory here - load in render() to avoid serialization issues
    }

    // Helper method to get permissions by category (not stored as property)
    protected function getPermissionsByCategory()
    {
        return Permission::where('is_active', true)
            ->orderBy('category')
            ->orderBy('display_name')
            ->get()
            ->groupBy('category');
    }

    // Create Permission Methods
    public function openCreatePermissionModal()
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can create permissions.');
            return;
        }

        $this->resetValidation();
        $this->resetCreatePermissionForm();
        $this->showCreatePermissionModal = true;
    }

    public function createPermission()
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can create permissions.');
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255|unique:permissions,name|regex:/^[a-z_.]+$/',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:' . implode(',', array_keys($this->categories)),
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'Permission name must contain only lowercase letters, dots, and underscores.',
        ]);

        Permission::create([
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'category' => $this->category,
            'is_active' => $this->is_active,
        ]);

        $this->loadStats();
        $this->resetCreatePermissionForm();
        $this->showCreatePermissionModal = false;
        session()->flash('message', 'Permission created successfully!');
    }

    // Edit Permission Methods
    public function openEditPermissionModal($permissionId)
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can edit permissions.');
            return;
        }

        $this->selectedPermission = Permission::findOrFail($permissionId);
        $this->resetValidation();
        
        $this->edit_name = $this->selectedPermission->name;
        $this->edit_display_name = $this->selectedPermission->display_name;
        $this->edit_description = $this->selectedPermission->description;
        $this->edit_category = $this->selectedPermission->category;
        $this->edit_is_active = $this->selectedPermission->is_active;

        $this->showEditPermissionModal = true;
    }

    public function updatePermission()
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can edit permissions.');
            return;
        }

        $this->validate([
            'edit_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($this->selectedPermission->id),
                'regex:/^[a-z_.]+$/'
            ],
            'edit_display_name' => 'required|string|max:255',
            'edit_description' => 'nullable|string|max:1000',
            'edit_category' => 'required|string|in:' . implode(',', array_keys($this->categories)),
            'edit_is_active' => 'boolean',
        ]);

        $this->selectedPermission->update([
            'name' => $this->edit_name,
            'display_name' => $this->edit_display_name,
            'description' => $this->edit_description,
            'category' => $this->edit_category,
            'is_active' => $this->edit_is_active,
        ]);

        // Clear permissions cache for all users
        if (!$this->edit_is_active) {
            DB::table('users')->update([
                'permissions_cache' => null,
                'permissions_updated_at' => null
            ]);
        }

        $this->loadStats();
        $this->showEditPermissionModal = false;
        session()->flash('message', 'Permission updated successfully!');
    }

    // Delete Permission Methods
    public function confirmDeletePermission($permissionId)
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can delete permissions.');
            return;
        }

        // Don't eager load roles to avoid serialization issues - use count instead
        $this->permissionToDelete = Permission::findOrFail($permissionId);
        $this->showDeletePermissionModal = true;
    }

    public function deletePermission()
    {
        if (!$this->permissionToDelete || !auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can delete permissions.');
            return;
        }

        // Check role count without loading the relationship
        if ($this->permissionToDelete->roles()->count() > 0) {
            session()->flash('error', 'Cannot delete permission that is assigned to roles. Please remove from roles first.');
            return;
        }

        $permissionName = $this->permissionToDelete->display_name;
        $this->permissionToDelete->delete();

        $this->loadStats();
        $this->showDeletePermissionModal = false;
        $this->permissionToDelete = null;
        session()->flash('message', "Permission '{$permissionName}' deleted successfully!");
    }

    // Permission Roles Methods
    public function openPermissionRolesModal($permissionId)
    {
        if (!auth()->user()->hasPermission('roles.view')) {
            session()->flash('error', 'You do not have permission to view roles.');
            return;
        }

        // Load permission without eager loading relationships to avoid serialization issues
        $this->selectedPermission = Permission::findOrFail($permissionId);
        
        // Load roles and convert to array to avoid Collection serialization issues
        $roles = $this->selectedPermission->roles()->get();
        $this->permissionRoles = $roles->map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'level' => $role->level,
            ];
        })->toArray();

        $this->showPermissionRolesModal = true;
    }

    public function removePermissionFromRole($roleId)
    {
        if (!auth()->user()->hasPermission('roles.edit')) {
            session()->flash('error', 'You do not have permission to manage role permissions.');
            return;
        }

        $role = Role::findOrFail($roleId);

        if (auth()->user()->role_level <= $role->level) {
            session()->flash('error', 'You cannot modify a role with level equal or higher than your own.');
            return;
        }

        $role->permissions()->detach($this->selectedPermission->id);
        
        // Clear permissions cache for users with this role - use direct update to avoid loading users
        $userIds = $role->users()->pluck('id');
        if ($userIds->isNotEmpty()) {
            \App\Models\User::whereIn('id', $userIds)->update([
                'permissions_cache' => null,
                'permissions_updated_at' => null
            ]);
        }

        // Refresh the permission roles list - convert to array to avoid serialization issues
        $this->selectedPermission->refresh();
        $roles = $this->selectedPermission->roles()->get();
        $this->permissionRoles = $roles->map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'level' => $role->level,
            ];
        })->toArray();

        session()->flash('message', 'Permission removed from role successfully!');
    }

    // Toggle permission status
    public function togglePermissionStatus($permissionId)
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can modify permissions.');
            return;
        }

        $permission = Permission::findOrFail($permissionId);
        $permission->update(['is_active' => !$permission->is_active]);

        // Clear permissions cache for all users if deactivated
        if (!$permission->is_active) {
            DB::table('users')->update([
                'permissions_cache' => null,
                'permissions_updated_at' => null
            ]);
        }

        $this->loadStats();
        
        $status = $permission->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Permission {$status} successfully!");
    }

    // Bulk Operations
    public function syncAllPermissions()
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can sync permissions.');
            return;
        }

        // Clear all permissions cache
        DB::table('users')->update([
            'permissions_cache' => null,
            'permissions_updated_at' => null
        ]);

        session()->flash('message', 'All user permissions synced successfully!');
    }

    public function createCategoryPermissions()
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Only super administrators can create permissions.');
            return;
        }

        // This would create a set of standard permissions for a category
        // Implementation depends on your specific needs
        session()->flash('message', 'Category permissions created successfully!');
    }

    // Reset methods
    public function resetCreatePermissionForm()
    {
        $this->reset([
            'name', 'display_name', 'description', 'category', 'is_active'
        ]);
        $this->category = 'general';
        $this->is_active = true;
    }

    // Search and filter updates
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        try {
            $query = Permission::query()
                ->when($this->search, function ($q) {
                    $q->where('display_name', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->when($this->categoryFilter, function ($q) {
                    $q->where('category', $this->categoryFilter);
                })
                ->when($this->statusFilter, function ($q) {
                    if ($this->statusFilter === 'active') {
                        $q->where('is_active', true);
                    } elseif ($this->statusFilter === 'inactive') {
                        $q->where('is_active', false);
                    }
                });

            // Load counts only (avoid loading full relationships to prevent serialization issues)
            $permissions = $query->withCount('roles')
                ->orderBy('category')
                ->orderBy('display_name')
                ->paginate(15);

            // Load permissions by category only when rendering (not stored as property)
            $permissionsByCategory = $this->getPermissionsByCategory();

            return view('livewire.admin.permission-management', [
                'permissions' => $permissions,
                'permissionsByCategory' => $permissionsByCategory,
            ]);
        } catch (\Exception $e) {
            \Log::error('PermissionManagement render error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return empty permissions by category on error
            $permissionsByCategory = collect();
            
            return view('livewire.admin.permission-management', [
                'permissions' => Permission::query()->paginate(15),
                'permissionsByCategory' => $permissionsByCategory,
            ]);
        }
    }
}
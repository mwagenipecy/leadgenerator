<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'View user list and details', 'category' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Create new users', 'category' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'description' => 'Edit existing users', 'category' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Delete users from system', 'category' => 'users'],
            ['name' => 'users.activate', 'display_name' => 'Activate/Deactivate Users', 'description' => 'Change user status', 'category' => 'users'],

            // Role Management
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'View roles and permissions', 'category' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'description' => 'Create new roles', 'category' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'description' => 'Edit existing roles', 'category' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'description' => 'Delete non-system roles', 'category' => 'roles'],
            ['name' => 'roles.assign', 'display_name' => 'Assign Roles', 'description' => 'Assign roles to users', 'category' => 'roles'],

            // Application Management
            ['name' => 'applications.view', 'display_name' => 'View Applications', 'description' => 'View loan applications', 'category' => 'applications'],
            ['name' => 'applications.create', 'display_name' => 'Create Applications', 'description' => 'Create loan applications', 'category' => 'applications'],
            ['name' => 'applications.edit', 'display_name' => 'Edit Applications', 'description' => 'Edit loan applications', 'category' => 'applications'],
            ['name' => 'applications.review', 'display_name' => 'Review Applications', 'description' => 'Review and process applications', 'category' => 'applications'],
            ['name' => 'applications.approve', 'display_name' => 'Approve Applications', 'description' => 'Approve loan applications', 'category' => 'applications'],
            ['name' => 'applications.reject', 'display_name' => 'Reject Applications', 'description' => 'Reject loan applications', 'category' => 'applications'],
            ['name' => 'applications.disburse', 'display_name' => 'Disburse Loans', 'description' => 'Process loan disbursements', 'category' => 'applications'],

            // Lender Management
            ['name' => 'lenders.view', 'display_name' => 'View Lenders', 'description' => 'View lender information', 'category' => 'lenders'],
            ['name' => 'lenders.create', 'display_name' => 'Create Lenders', 'description' => 'Register new lenders', 'category' => 'lenders'],
            ['name' => 'lenders.edit', 'display_name' => 'Edit Lenders', 'description' => 'Edit lender details', 'category' => 'lenders'],
            ['name' => 'lenders.approve', 'display_name' => 'Approve Lenders', 'description' => 'Approve lender registrations', 'category' => 'lenders'],
            ['name' => 'lenders.suspend', 'display_name' => 'Suspend Lenders', 'description' => 'Suspend lender accounts', 'category' => 'lenders'],

            // Loan Products
            ['name' => 'products.view', 'display_name' => 'View Loan Products', 'description' => 'View loan products', 'category' => 'products'],
            ['name' => 'products.create', 'display_name' => 'Create Loan Products', 'description' => 'Create new loan products', 'category' => 'products'],
            ['name' => 'products.edit', 'display_name' => 'Edit Loan Products', 'description' => 'Edit loan products', 'category' => 'products'],
            ['name' => 'products.delete', 'display_name' => 'Delete Loan Products', 'description' => 'Remove loan products', 'category' => 'products'],

            // Reports and Analytics
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'description' => 'Access reports and analytics', 'category' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'description' => 'Export report data', 'category' => 'reports'],
            ['name' => 'analytics.view', 'display_name' => 'View Analytics', 'description' => 'Access system analytics', 'category' => 'reports'],

            // System Administration
            ['name' => 'system.settings', 'display_name' => 'System Settings', 'description' => 'Manage system settings', 'category' => 'system'],
            ['name' => 'system.integrations', 'display_name' => 'Manage Integrations', 'description' => 'Configure system integrations', 'category' => 'system'],
            ['name' => 'system.audit', 'display_name' => 'View Audit Logs', 'description' => 'Access system audit trails', 'category' => 'system'],
            ['name' => 'system.maintenance', 'display_name' => 'System Maintenance', 'description' => 'Perform system maintenance', 'category' => 'system'],

            // Financial
            ['name' => 'commissions.view', 'display_name' => 'View Commissions', 'description' => 'View commission transactions', 'category' => 'financial'],
            ['name' => 'commissions.manage', 'display_name' => 'Manage Commissions', 'description' => 'Process commission payments', 'category' => 'financial'],
            ['name' => 'transactions.view', 'display_name' => 'View Transactions', 'description' => 'View transaction records', 'category' => 'financial'],
        ];

        // Insert permissions
        foreach ($permissions as &$permission) {
            $permission['created_at'] = $now;
            $permission['updated_at'] = $now;
        }
        DB::table('permissions')->insert($permissions);

        // Create Roles
        $roles = [
            [
                //'id'=>1,
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'level' => 100,
                'is_system_role' => true
            ],
            [
                //'id'=>2,    
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'System administrator with most permissions',
                'level' => 80,
                'is_system_role' => true
            ],
            [
               // 'id'=>3,
                'name' => 'lender',
                'display_name' => 'Lender',
                'description' => 'Lending institution user',
                'level' => 50,
                'is_system_role' => true
            ],
            [
               // 'id'=>4,
                'name' => 'borrower',
                'display_name' => 'Borrower',
                'description' => 'Loan applicant/borrower',
                'level' => 10,
                'is_system_role' => true
            ]
        ];

        // Insert roles
        foreach ($roles as &$role) {
            $role['created_at'] = $now;
            $role['updated_at'] = $now;
        }
        DB::table('roles')->insert($roles);

        // Get role IDs
        $superAdminId = DB::table('roles')->where('name', 'super_admin')->first()->id;
        $adminId = DB::table('roles')->where('name', 'admin')->first()->id;
        $lenderId = DB::table('roles')->where('name', 'lender')->first()->id;
        $borrowerId = DB::table('roles')->where('name', 'borrower')->first()->id;

        // Get all permission IDs
        $allPermissions = DB::table('permissions')->pluck('id');

        // Super Admin gets all permissions
        $superAdminPermissions = $allPermissions->map(function ($permissionId) use ($superAdminId, $now) {
            return [
                'role_id' => $superAdminId,
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now
            ];
        });

        // Admin permissions (most permissions except system maintenance)
        $adminPermissionNames = [
            'users.view', 'users.create', 'users.edit', 'users.activate',
            'roles.view', 'roles.assign',
            'applications.view', 'applications.edit', 'applications.review', 'applications.approve', 'applications.reject',
            'lenders.view', 'lenders.create', 'lenders.edit', 'lenders.approve', 'lenders.suspend',
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'reports.view', 'reports.export', 'analytics.view',
            'commissions.view', 'commissions.manage', 'transactions.view'
        ];
        $adminPermissionIds = DB::table('permissions')->whereIn('name', $adminPermissionNames)->pluck('id');
        $adminPermissions = $adminPermissionIds->map(function ($permissionId) use ($adminId, $now) {
            return [
                'role_id' => $adminId,
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now
            ];
        });

        // Lender permissions
        $lenderPermissionNames = [
            'applications.view', 'applications.review', 'applications.approve', 'applications.reject', 'applications.disburse',
            'products.view', 'products.create', 'products.edit',
            'reports.view', 'commissions.view', 'transactions.view'
        ];
        $lenderPermissionIds = DB::table('permissions')->whereIn('name', $lenderPermissionNames)->pluck('id');
        $lenderPermissions = $lenderPermissionIds->map(function ($permissionId) use ($lenderId, $now) {
            return [
                'role_id' => $lenderId,
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now
            ];
        });

        // Borrower permissions
        $borrowerPermissionNames = [
            'applications.view', 'applications.create', 'applications.edit'
        ];
        $borrowerPermissionIds = DB::table('permissions')->whereIn('name', $borrowerPermissionNames)->pluck('id');
        $borrowerPermissions = $borrowerPermissionIds->map(function ($permissionId) use ($borrowerId, $now) {
            return [
                'role_id' => $borrowerId,
                'permission_id' => $permissionId,
                'created_at' => $now,
                'updated_at' => $now
            ];
        });

        // Insert role permissions
        DB::table('role_permissions')->insert($superAdminPermissions->toArray());
        DB::table('role_permissions')->insert($adminPermissions->toArray());
        DB::table('role_permissions')->insert($lenderPermissions->toArray());
        DB::table('role_permissions')->insert($borrowerPermissions->toArray());
    }
}
<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds. Safe to run multiple times (no duplicates).
     */
    public function run(): void
    {
        // Permissions: firstOrCreate by name so CD can re-run without duplicate key errors
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

        foreach ($permissions as $data) {
            Permission::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['is_active' => true])
            );
        }

        // Roles: firstOrCreate by name
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Administrator', 'description' => 'Full system access with all permissions', 'level' => 100, 'is_system_role' => true],
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'System administrator with most permissions', 'level' => 80, 'is_system_role' => true],
            ['name' => 'lender', 'display_name' => 'Lender', 'description' => 'Lending institution user', 'level' => 50, 'is_system_role' => true],
            ['name' => 'borrower', 'display_name' => 'Borrower', 'description' => 'Loan applicant/borrower', 'level' => 10, 'is_system_role' => true],
        ];

        foreach ($roles as $data) {
            Role::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['is_active' => true])
            );
        }

        $superAdmin = Role::where('name', 'super_admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $lender = Role::where('name', 'lender')->first();
        $borrower = Role::where('name', 'borrower')->first();

        $allPermissionNames = Permission::pluck('name')->toArray();
        $adminPermissionNames = [
            'users.view', 'users.create', 'users.edit', 'users.activate',
            'roles.view', 'roles.assign',
            'applications.view', 'applications.edit', 'applications.review', 'applications.approve', 'applications.reject',
            'lenders.view', 'lenders.create', 'lenders.edit', 'lenders.approve', 'lenders.suspend',
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'reports.view', 'reports.export', 'analytics.view',
            'commissions.view', 'commissions.manage', 'transactions.view',
        ];
        $lenderPermissionNames = [
            'applications.view', 'applications.review', 'applications.approve', 'applications.reject', 'applications.disburse',
            'products.view', 'products.create', 'products.edit',
            'reports.view', 'commissions.view', 'transactions.view',
        ];
        $borrowerPermissionNames = ['applications.view', 'applications.create', 'applications.edit'];

        $superAdmin->syncPermissions($allPermissionNames);
        $admin->syncPermissions($adminPermissionNames);
        $lender->syncPermissions($lenderPermissionNames);
        $borrower->syncPermissions($borrowerPermissionNames);
    }
}
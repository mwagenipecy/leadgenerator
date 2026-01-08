<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminEmail = env('ADMIN_EMAIL', 'percyegno@gmail.com');
        
        if (User::where('email', $adminEmail)->exists()) {
            $this->command->info('Admin user already exists. Skipping...');
            return;
        }

        // Get super_admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();

        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found. Please run RolesAndPermissionsSeeder first.');
            return;
        }

        // Create admin user
        $adminUser = User::create([
            'name' => env('ADMIN_NAME', 'System Administrator'),
            'first_name' => env('ADMIN_FIRST_NAME', 'System'),
            'last_name' => env('ADMIN_LAST_NAME', 'Administrator'),
            'email' => $adminEmail,
            'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@123')),
            'email_verified_at' => now(),
            'role' => 'super_admin',
            'is_active' => true,
            'verification_status' => 'verified',
            'role_level' => 100,
        ]);

        // Assign super_admin role to the user (no assigned_by since this is the first user)
        $adminUser->assignRole($superAdminRole, null);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: ' . $adminEmail);
        $this->command->info('Password: ' . env('ADMIN_PASSWORD', 'Admin@123'));
        $this->command->warn('Please change the default password after first login!');
    }
}


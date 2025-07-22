<?php

namespace App\Livewire\Onboarding;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Register extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $nida_number = '';
    public $password = '';
    public $password_confirmation = '';
    public $terms = false;

    protected function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'nida_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'terms' => ['required', 'accepted'],
        ];
    }

    protected $messages = [
        'first_name.required' => 'First name is required.',
        'last_name.required' => 'Last name is required.',
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already registered.',
        'phone.required' => 'Phone number is required.',
        'nida_number.required' => 'NIDA number is required.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'password_confirmation.required' => 'Please confirm your password.',
        'terms.accepted' => 'You must agree to the terms and conditions.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function register()
    {
        $this->validate();
    
        try {
            $user = DB::transaction(function () {
                // Create the user
                $user = User::create([
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'name' => $this->first_name . ' ' . $this->last_name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'nida_number' => $this->nida_number,
                    'password' => Hash::make($this->password),
                    'email_verified_at' => now(), // Auto-verify super admin
                    'role' => 'borrower', // Set the legacy role field if still using it
                ]);
    
                // Assign Super Admin role using the new role system
                $this->assignSuperAdminRole($user);
    
                return $user;
            });
    
            // Auto-login the user
            auth()->login($user);
    
            // Log successful super admin creation
            \Log::info('Super Admin account created', [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ]);
    
            session()->flash('success', 'Super Admin account created successfully! You now have full system access.');
            
            // Reset form
            $this->reset();
            
            // Redirect to admin dashboard
            return redirect()->route('verification.options');
            
        } catch (\Exception $e) {
            // Log the detailed error
            \Log::error('Super Admin registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $this->email ?? 'N/A',
            ]);
            
            session()->flash('error', 'Registration failed. Please try again.');
        }
    }
    
    /**
     * Assign Super Admin role to user
     */
    private function assignSuperAdminRole($user)
    {
        // Try to get existing super admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if (!$superAdminRole) {
            // Create super admin role if it doesn't exist
            $superAdminRole = Role::create([
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'level' => 100,
                'is_system_role' => true,
                'is_active' => true,
            ]);
    
            // Assign all permissions to super admin role
            $allPermissions = \App\Models\Permission::where('is_active', true)->pluck('id');
            if ($allPermissions->isNotEmpty()) {
                $superAdminRole->permissions()->sync($allPermissions);
            }
        }
    
        // Assign role to user
        if (method_exists($user, 'assignRole')) {
            // Use the trait method if available
            $user->assignRole($superAdminRole);
        } else {
            // Direct database assignment
            $user->roles()->syncWithoutDetaching([
                $superAdminRole->id => [
                    'assigned_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    
        // Update user's role level
        $user->update(['role_level' => $superAdminRole->level]);
    }

    


    public function render()
    {
        return view('livewire.onboarding.register');
    }
}
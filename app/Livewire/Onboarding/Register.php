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
    public $type = 'individual';
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $nida_number = '';
    public $company_name = '';
    public $company_tin = '';
    public $company_contact_nida = '';
    public $country = '';
    public $passport_number = '';
    public $password = '';
    public $password_confirmation = '';
    public $terms = false;

    protected function rules()
    {
        $isCompany = $this->type === 'company';

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20','unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'terms' => ['required', 'accepted'],
        ];

        if ($isCompany) {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['company_tin'] = ['required', 'string', 'max:50'];
            $rules['country'] = ['required', 'string', 'max:255'];
            // If Tanzania, require NIDA; if not, require passport
            if (strtolower($this->country ?? '') === 'tanzania') {
                $rules['company_contact_nida'] = ['required', 'string', 'size:20', 'regex:/^[0-9]{20}$/'];
            } else {
                $rules['passport_number'] = ['required', 'string', 'max:50'];
            }
        } else {
            $rules['nida_number'] = ['required', 'string', 'size:20', 'unique:users', 'regex:/^[0-9]{20}$/'];
        }

        return $rules;
    }

    public function mount(): void
    {
        if (request()->routeIs('company.register')) {
            $this->type = 'company';
        } else {
            $this->type = 'individual';
        }
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
                // For company users from Tanzania, set nida_number from company_contact_nida for NIDA verification
                $nidaNumber = $this->type === 'individual' ? $this->nida_number : null;
                $companyContactNida = $this->type === 'company' && strtolower($this->country ?? '') === 'tanzania' ? $this->company_contact_nida : null;
                
                // Set nida_number for company users to enable NIDA verification
                if ($this->type === 'company' && !empty($companyContactNida)) {
                    $nidaNumber = $companyContactNida;
                }
                
                $user = User::create([
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'name' => $this->first_name . ' ' . $this->last_name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'nida_number' => $nidaNumber,
                    'company_name' => $this->type === 'company' ? $this->company_name : null,
                    'company_tin' => $this->type === 'company' ? $this->company_tin : null,
                    'company_contact_nida' => $companyContactNida,
                    'country' => $this->type === 'company' ? $this->country : null,
                    'passport_number' => $this->type === 'company' && strtolower($this->country ?? '') !== 'tanzania' ? $this->passport_number : null,
                    'registration_type' => $this->type,
                    'password' => Hash::make($this->password),
                    'email_verified_at' => now(),
                    'verification_status' => $this->type === 'individual' ? 'pending' : 'pending',
                    'company_verification_status' => $this->type === 'company' ? 'pending' : null,
                    'role' => 'borrower',
                ]);
    
                // Assign borrower role
                $borrowerRole = Role::where('name', 'borrower')->first();
                if ($borrowerRole) {
                    $user->assignRole($borrowerRole);
                }
    
                return $user;
            });
    
            // For company registration, redirect to KYC verification
            if ($this->type === 'company') {
                // Auto-login the user
                auth()->login($user);
                
                session()->flash('success', 'Account created successfully! Please complete your company KYC verification.');
                
                // Redirect to company KYC verification page
                return redirect()->route('company.kyc');
            }
    
            // For individual registration, continue with normal flow
            auth()->login($user);
            
            session()->flash('success', 'Account created successfully!');
            
            // Reset form
            $this->reset();
            
            // Redirect to verification options
            return redirect()->route('verification.options');
            
        } catch (\Exception $e) {
            // Log the detailed error
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $this->email ?? 'N/A',
            ]);
            
            session()->flash('error', 'Registration failed. Please try again.');
        }
    }

    


    public function render()
    {
        return view('livewire.onboarding.register');
    }
}
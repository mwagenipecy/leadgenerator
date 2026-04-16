<?php

namespace App\Livewire\Onboarding;

use App\Models\Role;
use App\Services\OtpService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Livewire\Concerns\WithLocale;

class Register extends Component
{
    use WithLocale;
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
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\'\.]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\'\.]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'phone' => ['required', 'string', 'max:20', 'unique:users', 'regex:/^[\+]?[0-9\s\-\(\)]+$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'terms' => ['required', 'accepted'],
        ];

        if ($isCompany) {
            $rules['company_name'] = ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-\'\.&,()]+$/'];
            $rules['company_tin'] = ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\-\s]+$/'];
            $rules['country'] = ['required', 'string', 'max:255', 'in:Tanzania,Kenya,Uganda,Rwanda,Other'];
            // If Tanzania, require NIDA; if not, require passport
            if (strtolower($this->country ?? '') === 'tanzania') {
                $rules['company_contact_nida'] = ['required', 'string', 'size:20', 'regex:/^[0-9]{20}$/'];
            } else {
                $rules['passport_number'] = ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9]+$/'];
            }
        } else {
            $rules['nida_number'] = ['required', 'string', 'size:20', 'unique:users', 'regex:/^[0-9]{20}$/'];
        }

        return $rules;
    }

    public function mount(): void
    {
        // Check route first
        if (request()->routeIs('company.register')) {
            $this->type = 'company';
        } 
        // Then check query parameter
        elseif (request()->has('type') && in_array(request()->get('type'), ['individual', 'company'])) {
            $this->type = request()->get('type');
        } 
        // Default to individual
        else {
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

    public function switchType($newType)
    {
        // Validate the type
        if (!in_array($newType, ['individual', 'company'])) {
            return;
        }
        
        // Set the type
        $this->type = $newType;
        
        // Reset form fields when switching between individual and company
        $this->reset(['company_name', 'company_tin', 'company_contact_nida', 'country', 'passport_number', 'nida_number']);
        $this->resetErrorBag();
    }
    
    public function updatedType($value)
    {
        // This will be called automatically when type changes via wire:model
        // Reset form fields when switching between individual and company
        if (in_array($value, ['individual', 'company'])) {
            $this->reset(['company_name', 'company_tin', 'company_contact_nida', 'country', 'passport_number', 'nida_number']);
            $this->resetErrorBag();
        }
    }

    public function updated($propertyName)
    {
        // Sanitize input before validation
        $this->sanitizeInput($propertyName);
        $this->validateOnly($propertyName);
    }

    /**
     * Sanitize a single input field
     */
    protected function sanitizeInput($propertyName)
    {
        if (!property_exists($this, $propertyName)) {
            return;
        }

        $value = $this->$propertyName;

        // Skip sanitization for boolean and password fields
        if (in_array($propertyName, ['terms', 'password', 'password_confirmation'])) {
            return;
        }

        if (is_string($value)) {
            // Strip HTML tags
            $value = strip_tags($value);
            
            // Trim whitespace
            $value = trim($value);
            
            // Remove null bytes and other dangerous characters
            $value = str_replace(["\0", "\r", "\n"], '', $value);
            
            // For NIDA fields, remove dashes and keep only numbers
            if (in_array($propertyName, ['nida_number', 'company_contact_nida'])) {
                $value = preg_replace('/[^0-9]/', '', $value);
            }
            
            // Prevent SQL injection patterns (basic check)
            $dangerousPatterns = [
                '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE|UNION|SCRIPT|JAVASCRIPT|ONLOAD|ONERROR)\b)/i',
                '/(<script|<\/script>|javascript:|on\w+\s*=)/i',
            ];
            
            foreach ($dangerousPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    $value = preg_replace($pattern, '', $value);
                }
            }
            
            $this->$propertyName = $value;
        }
    }

    /**
     * Sanitize all inputs before processing
     */
    protected function sanitizeAllInputs()
    {
        $fields = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'nida_number',
            'company_name',
            'company_tin',
            'company_contact_nida',
            'country',
            'passport_number',
        ];

        foreach ($fields as $field) {
            $this->sanitizeInput($field);
        }
    }

    public function register()
    {
        // Sanitize all inputs before validation
        $this->sanitizeAllInputs();
        
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
                    'verification_status' => 'pending',
                    // company_verification_status will use database default 'pending'
                    'role' => 'borrower',
                ]);
    
                // Assign borrower role
                $borrowerRole = Role::where('name', 'borrower')->first();
                if ($borrowerRole) {
                    $user->assignRole($borrowerRole);
                }
    
                return $user;
            });
    
            // Auto-login the user first so any auth listeners keep working.
            Auth::login($user);
            
            // Store user details for OTP flow, then log out until OTP verification completes.
            Session::forget('otp_verified');
            Session::put('otp_user_id', $user->id);
            Session::put('login_timestamp', now()->timestamp);
            
            $guard = Auth::guard();
            if (method_exists($guard, 'logout')) {
                $guard->logout();
            }
            
            $otpService = app(OtpService::class);
            if (!$otpService->generateAndSendOtp($user)) {
                Session::forget(['otp_user_id', 'login_timestamp']);
                session()->flash('error', 'Account created, but failed to send verification code. Please login and try again.');
                return redirect()->route('login');
            }
            
            session()->flash('success', 'Account created successfully! Please check your email for the verification code.');
            
            // Reset form
            $this->reset();
            
            // Redirect to OTP page; post-OTP flow remains handled in OtpController.
            return redirect()->route('otp.show');
            
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
        // Ensure locale is set from session before rendering
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        }
        
        return view('livewire.onboarding.register');
    }
}
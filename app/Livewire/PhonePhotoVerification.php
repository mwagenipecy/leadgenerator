<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\NidaVerification;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhonePhotoVerification extends Component
{
    use WithFileUploads;

    public $photo;
    public $photoType = 'fingerprint'; // Default to fingerprint only
    public $photoPreview = '';
    public $isProcessing = false;
    public $isVerified = false;
    public $errorMessage = '';
    public $successMessage = '';
    public $verificationStep = 'capture'; // Start directly at capture, skip selection

    protected $rules = [
        'photo' => 'required|image|max:5120', // 5MB max
    ];

    public function render()
    {
        return view('livewire.phone-photo-verification');
    }

    public function selectPhotoType($type)
    {
        $this->photoType = $type;
        $this->verificationStep = 'capture';
        $this->resetErrorsAndMessages();
    }

    public function updatedPhoto()
    {
        $this->validatePhoto();
        if (!$this->errorMessage) {
            $this->processPhoto();
        }
    }

    private function validatePhoto()
    {
        try {
            $this->validate();
            $this->errorMessage = '';
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errorMessage = 'Please select a valid image file (max 5MB).';
        }
    }

    public function processPhoto()
    {
        if (!$this->photo) {
            return;
        }

        $this->isProcessing = true;
        $this->verificationStep = 'processing';
        $this->errorMessage = '';

        try {
            // Get photo preview for UI
            $this->photoPreview = $this->photo->temporaryUrl();
            
            // Store the photo
            $photoPath = $this->photo->store('verification-photos', 'private');
            
            // Simulate processing delay for better UX
            sleep(2);
            
            // Verify with NIDA service
            $verificationResult = $this->verifyPhotoWithNida($photoPath, $this->photoType);
            
            if ($verificationResult['success']) {
                $this->saveVerificationRecord($photoPath, $verificationResult);
                $this->completeVerification();
            } else {
                // Clean up failed photo
                Storage::disk('private')->delete($photoPath);
                $this->showError($verificationResult['message'] ?? 'Photo verification failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            $this->showError('Failed to process photo. Please try again.');
            \Log::error('Photo verification error: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    private function verifyPhotoWithNida($photoPath, $photoType)
    {
        // Simulate NIDA API verification
        try {
            // Simulate API processing time
            sleep(1);
            
            // Basic image validation
            $photoContent = Storage::disk('private')->get($photoPath);
            $imageInfo = getimagesizefromstring($photoContent);
            
            if (!$imageInfo) {
                return [
                    'success' => false,
                    'message' => 'Invalid image format. Please capture a clear photo.'
                ];
            }
            
            // Check image dimensions (minimum requirements)
            if ($imageInfo[0] < 300 || $imageInfo[1] < 300) {
                return [
                    'success' => false,
                    'message' => 'Image resolution too low. Please capture a higher quality photo.'
                ];
            }
            
            // Simulate verification success rate (85% for demo)
            $success = rand(1, 100) <= 85;
            
            if ($success) {
                return [
                    'success' => true,
                    'message' => 'Photo verified successfully',
                    'confidence_score' => rand(85, 99),
                    'extracted_data' => [
                        'photo_type' => $photoType,
                        'quality_score' => rand(80, 100),
                        'verified_at' => now()->toISOString(),
                        'nida_match' => true
                    ]
                ];
            } else {
                $errorMessages = [
                    'Photo quality insufficient. Please ensure good lighting and clear image.',
                    'Unable to verify identity from the provided photo. Please retake.',
                    'Photo does not match NIDA records. Please ensure you are using your own document.',
                    'Image too blurry or dark. Please capture in better lighting conditions.'
                ];
                
                return [
                    'success' => false,
                    'message' => $errorMessages[array_rand($errorMessages)]
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Verification service temporarily unavailable. Please try again later.'
            ];
        }
    }

    private function saveVerificationRecord($photoPath, $verificationResult)
    {
        // Get the correct NIDA number (use company_contact_nida for company users)
        $user = Auth::user();
        $nidaNumber = $user->nida_number;
        if (empty($nidaNumber) && $user->registration_type === 'company' && !empty($user->company_contact_nida)) {
            $nidaNumber = $user->company_contact_nida;
        }
        
        NidaVerification::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nida_number' => $nidaNumber,
                'status' => 'verified',
                'verified_at' => now(),
                'verification_method' => 'phone_photo',
                'photo_type' => $this->photoType,
                'photo_path' => $photoPath,
                'nida_response' => $verificationResult,
                'confidence_score' => $verificationResult['confidence_score'] ?? null,
                'expires_at' => now()->addYears(5), // Verification valid for 5 years
            ]
        );
    }

    private function completeVerification()
    {
        $user = Auth::user();
        
        // For company users, ensure nida_number is set from company_contact_nida
        $updateData = [
            'nida_verified_at' => now(),
            'verification_status' => 'verified'
        ];
        
        // If company user and nida_number is not set, use company_contact_nida
        if ($user->registration_type === 'company' && empty($user->nida_number) && !empty($user->company_contact_nida)) {
            $updateData['nida_number'] = $user->company_contact_nida;
        }
        
        // Update user record
        $user->update($updateData);
        
        // Create or update user profile (skip for company users during KYC)
        if ($user->registration_type !== 'company') {
            $this->createOrUpdateUserProfile($user);
        }
        
        // Dispatch credit score fetch job for individual users after NIDA verification
        if ($user->registration_type === 'individual' && !empty($user->nida_number)) {
            \App\Jobs\FetchCreditScore::dispatch($user->id);
            \Illuminate\Support\Facades\Log::info('PhonePhotoVerification: Dispatched FetchCreditScore job after NIDA verification', [
                'user_id' => $user->id,
            ]);
        }
        
        $this->isVerified = true;
        $this->verificationStep = 'complete';
        
        // Different messages and redirects for company vs individual users
        if ($user->registration_type === 'company') {
            $this->successMessage = 'Your NIDA verification is complete! You can now continue with company document upload.';
        } else {
            $this->successMessage = 'Your identity has been successfully verified! Your profile has been created. You can now complete your profile information.';
        }
        
        // Clear any errors
        $this->errorMessage = '';
    }

    public function goToProfile()
    {
        $user = Auth::user();
        
        // For company users, redirect to company KYC page
        if ($user->registration_type === 'company') {
            return redirect()->route('company.kyc')
                ->with('success', 'NIDA verification completed! Please continue with document upload.');
        }
        
        return redirect()->route('loan-application.profile');
    }

    private function createOrUpdateUserProfile($user)
    {
        try {
            // Get or create user profile
            $profile = UserProfile::firstOrNew(['user_id' => $user->id]);
            
            // Update profile with user information from users table
            $profileData = [
                'first_name' => $user->first_name ?? $user->name ?? '',
                'last_name' => $user->last_name ?? '',
                'national_id' => $user->nida_number ?? '',
                'email' => $user->email ?? '',
                'phone_number' => $user->phone ?? '',
                'last_updated' => now(),
            ];
            
            // Handle date_of_birth - store as string in Y-m-d format
            if ($user->date_of_birth) {
                try {
                    if ($user->date_of_birth instanceof \Carbon\Carbon || $user->date_of_birth instanceof \DateTime) {
                        $profileData['date_of_birth'] = $user->date_of_birth->format('Y-m-d');
                    } elseif (is_string($user->date_of_birth)) {
                        // Try to parse and format the date string
                        $date = \Carbon\Carbon::parse($user->date_of_birth);
                        $profileData['date_of_birth'] = $date->format('Y-m-d');
                    } else {
                        $profileData['date_of_birth'] = $user->date_of_birth;
                    }
                } catch (\Exception $e) {
                    // If date parsing fails, set to null
                    $profileData['date_of_birth'] = null;
                }
            } else {
                $profileData['date_of_birth'] = null;
            }
            
            // If profile doesn't exist, set user_id
            if (!$profile->exists) {
                $profileData['user_id'] = $user->id;
            }
            
            // Update profile
            $profile->fill($profileData);
            $profile->save();
            
            // Calculate completion percentage
            $profile->calculateCompletionPercentage();
            
        } catch (\Exception $e) {
            // Log error but don't fail verification
            \Log::error('Error creating/updating user profile: ' . $e->getMessage());
        }
    }

    private function showError($message)
    {
        $this->errorMessage = $message;
        $this->verificationStep = 'capture';
        $this->isProcessing = false;
        
        // Reset photo for retry
        $this->photo = null;
        $this->photoPreview = '';
    }

    public function retryVerification()
    {
        $this->resetErrorsAndMessages();
        $this->verificationStep = 'capture'; // Go back to capture, not select
        $this->photo = null;
        $this->photoPreview = '';
        $this->photoType = 'fingerprint'; // Keep fingerprint as default
        $this->isProcessing = false;
    }

    public function redirectToDashboard()
    {
        return redirect()->route('dashboard');
    }

    public function backToMethodSelection()
    {
        return redirect()->route('verification.options');
    }

    private function resetErrorsAndMessages()
    {
        $this->errorMessage = '';
        $this->successMessage = '';
        $this->resetValidation();
    }
}
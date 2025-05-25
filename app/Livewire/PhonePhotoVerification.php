<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\NidaVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhonePhotoVerification extends Component
{
    use WithFileUploads;

    public $photo;
    public $photoType = '';
    public $photoPreview = '';
    public $isProcessing = false;
    public $isVerified = false;
    public $errorMessage = '';
    public $successMessage = '';
    public $verificationStep = 'select'; // 'select', 'capture', 'processing', 'complete'

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
        NidaVerification::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nida_number' => Auth::user()->nida_number,
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
        // Update user record
        Auth::user()->update([
            'nida_verified_at' => now(),
            'verification_status' => 'verified'
        ]);
        
        $this->isVerified = true;
        $this->verificationStep = 'complete';
        $this->successMessage = 'Your identity has been successfully verified!';
        
        // Clear any errors
        $this->errorMessage = '';
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
        $this->verificationStep = 'select';
        $this->photo = null;
        $this->photoPreview = '';
        $this->photoType = '';
        $this->isProcessing = false;
    }

    public function redirectToDashboard()
    {
        return redirect()->route('dashboard');
    }

    public function backToMethodSelection()
    {
        return redirect()->route('verification.method');
    }

    private function resetErrorsAndMessages()
    {
        $this->errorMessage = '';
        $this->successMessage = '';
        $this->resetValidation();
    }
}
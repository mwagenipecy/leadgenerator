<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NidaVerification;
use Illuminate\Support\Facades\Auth;

class QuestionnaireVerification extends Component
{
    public $questionnaireAnswers = [
        'dob_verification' => '',
        'birth_place' => '',
        'father_name' => '',
        'mother_name' => '',
        'birth_region' => '',
    ];

    public $isProcessing = false;
    public $isVerified = false;
    public $errorMessage = '';
    public $successMessage = '';
    public $currentQuestion = 1;
    public $totalQuestions = 5;

    // Available regions for questionnaire
    public $regions = [
        'arusha' => 'Arusha',
        'dar-es-salaam' => 'Dar es Salaam',
        'dodoma' => 'Dodoma',
        'geita' => 'Geita',
        'iringa' => 'Iringa',
        'kagera' => 'Kagera',
        'katavi' => 'Katavi',
        'kigoma' => 'Kigoma',
        'kilimanjaro' => 'Kilimanjaro',
        'lindi' => 'Lindi',
        'manyara' => 'Manyara',
        'mara' => 'Mara',
        'mbeya' => 'Mbeya',
        'morogoro' => 'Morogoro',
        'mtwara' => 'Mtwara',
        'mwanza' => 'Mwanza',
        'njombe' => 'Njombe',
        'pemba-north' => 'Pemba North',
        'pemba-south' => 'Pemba South',
        'pwani' => 'Pwani',
        'rukwa' => 'Rukwa',
        'ruvuma' => 'Ruvuma',
        'shinyanga' => 'Shinyanga',
        'simiyu' => 'Simiyu',
        'singida' => 'Singida',
        'songwe' => 'Songwe',
        'tabora' => 'Tabora',
        'tanga' => 'Tanga',
        'unguja-north' => 'Unguja North',
        'unguja-south' => 'Unguja South',
        'unguja-west' => 'Unguja West',
    ];

    protected $rules = [
        'questionnaireAnswers.dob_verification' => 'required|date|before:today',
        'questionnaireAnswers.birth_place' => 'required|string|min:2|max:100',
        'questionnaireAnswers.father_name' => 'required|string|min:2|max:100',
        'questionnaireAnswers.mother_name' => 'required|string|min:2|max:100',
        'questionnaireAnswers.birth_region' => 'required|string',
    ];

    protected $messages = [
        'questionnaireAnswers.dob_verification.required' => 'Date of birth is required',
        'questionnaireAnswers.dob_verification.before' => 'Date of birth must be in the past',
        'questionnaireAnswers.birth_place.required' => 'Place of birth is required',
        'questionnaireAnswers.birth_place.min' => 'Place of birth must be at least 2 characters',
        'questionnaireAnswers.father_name.required' => "Father's name is required",
        'questionnaireAnswers.father_name.min' => "Father's name must be at least 2 characters",
        'questionnaireAnswers.mother_name.required' => "Mother's name is required",
        'questionnaireAnswers.mother_name.min' => "Mother's name must be at least 2 characters",
        'questionnaireAnswers.birth_region.required' => 'Birth region is required',
    ];

    public function mount()
    {
        // Check if user is already verified
        if (Auth::user()->isNidaVerified()) {
            $this->isVerified = true;
            $this->successMessage = 'Your identity has already been verified.';
        }
    }

    public function render()
    {
        return view('livewire.questionnaire-verification');
    }

    public function submitQuestionnaire()
    {
        $this->validate();
        
        $this->isProcessing = true;
        $this->errorMessage = '';
        
        try {
            // Verify answers with NIDA
            $verificationResult = $this->verifyQuestionnaireWithNida($this->questionnaireAnswers);
            
            if ($verificationResult['success']) {
                $this->saveVerificationRecord($verificationResult);
                $this->completeVerification();
            } else {
                $this->errorMessage = $verificationResult['message'] ?? 'Some answers do not match our records. Please verify your information and try again.';
            }
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Verification failed. Please try again.';
            \Log::error('Questionnaire verification error: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    private function verifyQuestionnaireWithNida($answers)
    {
        // Simulate NIDA API verification of questionnaire answers
        try {
            $user = Auth::user();
            
            // Simulate processing time
            sleep(2);
            
            // Calculate match score based on answers
            $matchScore = 0;
            $totalPossibleScore = 100;
            $scorePerQuestion = $totalPossibleScore / count($answers);
            
            // Check date of birth (if user has it in profile)
            if (!empty($user->date_of_birth) && $user->date_of_birth->format('Y-m-d') === $answers['dob_verification']) {
                $matchScore += $scorePerQuestion;
            }
            
            // Basic validation for other answers (check if they're not empty and reasonable)
            foreach (['birth_place', 'father_name', 'mother_name', 'birth_region'] as $field) {
                if (!empty($answers[$field]) && strlen($answers[$field]) >= 2) {
                    // Simulate verification logic - in real app, this would check against NIDA database
                    $matchScore += $scorePerQuestion * 0.8; // Give partial credit for properly formatted answers
                }
            }
            
            // Add some randomness to simulate real verification (between 0-20 points)
            $randomBonus = rand(0, 20);
            $matchScore = min($totalPossibleScore, $matchScore + $randomBonus);
            
            // Require at least 70% match for verification success
            $threshold = 70;
            
            if ($matchScore >= $threshold) {
                return [
                    'success' => true,
                    'message' => 'Identity verified successfully through questionnaire',
                    'match_score' => $matchScore,
                    'verified_fields' => array_keys($answers),
                    'verification_method' => 'questionnaire'
                ];
            } else {
                $failureReasons = [
                    'One or more answers do not match our records exactly.',
                    'The provided information could not be verified against NIDA database.',
                    'Please ensure all information matches your official NIDA records.',
                    'Some details require correction. Please review and try again.'
                ];
                
                return [
                    'success' => false,
                    'message' => $failureReasons[array_rand($failureReasons)],
                    'match_score' => $matchScore,
                    'threshold' => $threshold
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Verification service temporarily unavailable. Please try again later.'
            ];
        }
    }

    private function saveVerificationRecord($verificationResult)
    {
        NidaVerification::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'nida_number' => Auth::user()->nida_number,
                'status' => 'verified',
                'verified_at' => now(),
                'verification_method' => 'questionnaire',
                'questionnaire_answers' => $this->questionnaireAnswers,
                'nida_response' => $verificationResult,
                'match_score' => $verificationResult['match_score'] ?? null,
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
        $this->successMessage = 'Your identity has been successfully verified through the questionnaire!';
        
        // Clear any errors
        $this->errorMessage = '';
    }

    public function retryVerification()
    {
        $this->resetErrorsAndMessages();
        $this->questionnaireAnswers = [
            'dob_verification' => '',
            'birth_place' => '',
            'father_name' => '',
            'mother_name' => '',
            'birth_region' => '',
        ];
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

    // Real-time validation for individual fields
    public function updated($propertyName)
    {
        if (strpos($propertyName, 'questionnaireAnswers.') === 0) {
            $this->validateOnly($propertyName);
        }
    }

    // Get progress percentage
    public function getProgressPercentage()
    {
        $answeredQuestions = 0;
        foreach ($this->questionnaireAnswers as $answer) {
            if (!empty($answer)) {
                $answeredQuestions++;
            }
        }
        return ($answeredQuestions / $this->totalQuestions) * 100;
    }

    // Check if form is complete
    public function isFormComplete()
    {
        return $this->getProgressPercentage() === 100.0;
    }

    // Get helper text for questions
    public function getQuestionHelper($questionKey)
    {
        $helpers = [
            'dob_verification' => 'Enter the exact date of birth as registered with NIDA',
            'birth_place' => 'Enter the place of birth as it appears on your NIDA records',
            'father_name' => 'Enter your father\'s full name as registered with NIDA',
            'mother_name' => 'Enter your mother\'s maiden name (before marriage)',
            'birth_region' => 'Select the region where you were born',
        ];

        return $helpers[$questionKey] ?? '';
    }
}
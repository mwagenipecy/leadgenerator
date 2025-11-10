<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NidaVerification;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

class QuestionnaireVerification extends Component
{
    public $questionnaireAnswers = [
        'dob_verification' => '',
        'father_name' => '',
        'mother_name' => '',
    ];

    public $answeredQuestions = [
        'dob_verification' => false,
        'father_name' => false,
        'mother_name' => false,
    ];

    public $questionResults = [
        'dob_verification' => null, // null = not answered, true = correct, false = incorrect
        'father_name' => null,
        'mother_name' => null,
    ];

    public $isProcessing = false;
    public $isVerified = false;
    public $errorMessage = '';
    public $successMessage = '';
    public $currentQuestion = 1;
    public $totalQuestions = 3;
    public $currentAnswer = '';
    public $showFinalResult = false;
    public $correctAnswersCount = 0;

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
        'questionnaireAnswers.father_name' => 'required|string|min:2|max:100',
        'questionnaireAnswers.mother_name' => 'required|string|min:2|max:100',
    ];

    protected $messages = [
        'questionnaireAnswers.dob_verification.required' => 'Date of birth is required',
        'questionnaireAnswers.dob_verification.before' => 'Date of birth must be in the past',
        'questionnaireAnswers.father_name.required' => "Father's name is required",
        'questionnaireAnswers.father_name.min' => "Father's name must be at least 2 characters",
        'questionnaireAnswers.mother_name.required' => "Mother's name is required",
        'questionnaireAnswers.mother_name.min' => "Mother's name must be at least 2 characters",
    ];

    public function mount()
    {
        // Initialize with empty answers
        $this->questionnaireAnswers = [
            'dob_verification' => '',
            'father_name' => '',
            'mother_name' => '',
        ];
        
        $this->answeredQuestions = [
            'dob_verification' => false,
            'father_name' => false,
            'mother_name' => false,
        ];

        $this->questionResults = [
            'dob_verification' => null,
            'father_name' => null,
            'mother_name' => null,
        ];
        
        $this->currentQuestion = 1;
        $this->currentAnswer = '';
        
        // Check if user has already completed questionnaire verification specifically
        $user = Auth::user();
        if ($user) {
            $questionnaireVerification = NidaVerification::where('user_id', $user->id)
                ->where('verification_method', 'questionnaire')
                ->where('status', 'verified')
                ->first();
            
            if ($questionnaireVerification) {
                // User has already completed questionnaire verification
                $this->isVerified = true;
                $this->successMessage = 'Your identity has been successfully verified through the questionnaire!';
            } else {
                // Show the form to complete questionnaire verification
                $this->isVerified = false;
                $this->isProcessing = false;
                $this->currentQuestion = 1;
            }
        } else {
            // Ensure form is visible
            $this->isVerified = false;
            $this->isProcessing = false;
            $this->currentQuestion = 1;
        }
    }

    public function render()
    {
        return view('livewire.questionnaire-verification');
    }

    public function submitCurrentQuestion()
    {
        $questionKey = $this->getCurrentQuestionKey();
        
        // Validate current question
        $this->validate([
            "questionnaireAnswers.{$questionKey}" => $this->getValidationRule($questionKey)
        ]);
        
        $this->isProcessing = true;
        $this->errorMessage = '';
        
        try {
            // Verify current answer
            $isCorrect = $this->verifySingleAnswer($questionKey, $this->questionnaireAnswers[$questionKey]);
            
            // Store result
            $this->questionResults[$questionKey] = $isCorrect;
            $this->answeredQuestions[$questionKey] = true;
            
            if ($isCorrect) {
                $this->correctAnswersCount++;
            }
            
            // Check if all questions are answered
            if ($this->areAllQuestionsAnswered()) {
                // All questions answered, check if at least 2 are correct
                if ($this->correctAnswersCount >= 2) {
                    // Success - at least 2 correct answers
                    $this->processFinalVerification();
                } else {
                    // Failed - less than 2 correct answers
                    $this->showFinalResult = true;
                    $this->errorMessage = 'Verification failed. You need at least 2 correct answers out of 3 questions.';
                }
            } else {
                // Move to next question
                $this->currentQuestion++;
                $this->currentAnswer = '';
                $this->errorMessage = ''; // Clear any errors
                $this->resetValidation(); // Reset validation state
            }
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Verification failed. Please try again.';
            \Log::error('Questionnaire verification error: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    private function getCurrentQuestionKey()
    {
        $questions = ['dob_verification', 'father_name', 'mother_name'];
        return $questions[$this->currentQuestion - 1] ?? 'dob_verification';
    }

    private function getValidationRule($questionKey)
    {
        $rules = [
            'dob_verification' => 'required|date|before:today',
            'father_name' => 'required|string|min:2|max:100',
            'mother_name' => 'required|string|min:2|max:100',
        ];
        return $rules[$questionKey] ?? 'required';
    }

    private function verifySingleAnswer($questionKey, $answer)
    {
        // Simulate verification - in real app, this would check against NIDA database
        $user = Auth::user();
        
        // Simulate processing time
        sleep(1);
        
        // For demo purposes, we'll simulate verification
        // In a real app, you would verify against NIDA database
        switch ($questionKey) {
            case 'dob_verification':
                // Check if date of birth matches (if user has it in profile)
                if (!empty($user->date_of_birth) && $user->date_of_birth->format('Y-m-d') === $answer) {
                    return true;
                }
                // For demo: randomly return true/false (70% success rate)
                return rand(1, 100) <= 70;
                
            case 'father_name':
            case 'mother_name':
                // For demo: check if answer is not empty and has reasonable length
                if (!empty($answer) && strlen($answer) >= 2) {
                    // Simulate 70% success rate for demo
                    return rand(1, 100) <= 70;
                }
                return false;
                
            default:
                return false;
        }
    }

    private function areAllQuestionsAnswered()
    {
        foreach ($this->answeredQuestions as $answered) {
            if (!$answered) {
                return false;
            }
        }
        return true;
    }

    private function processFinalVerification()
    {
        // All questions answered and at least 2 are correct
        $verificationResult = [
            'success' => true,
            'message' => 'Identity verified successfully through questionnaire',
            'match_score' => ($this->correctAnswersCount / $this->totalQuestions) * 100,
            'correct_answers' => $this->correctAnswersCount,
            'total_questions' => $this->totalQuestions,
            'verified_fields' => array_keys($this->questionnaireAnswers),
            'verification_method' => 'questionnaire'
        ];
        
        $this->saveVerificationRecord($verificationResult);
        $this->completeVerification();
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
            foreach (['father_name', 'mother_name'] as $field) {
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
        
        $this->isVerified = true;
        
        // Different messages for company vs individual users
        if ($user->registration_type === 'company') {
            $this->successMessage = 'Your NIDA verification is complete! You can now continue with company document upload.';
        } else {
            $this->successMessage = 'Your identity has been successfully verified through the questionnaire! Your profile has been created. You can now complete your profile information.';
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

    public function retryVerification()
    {
        $this->resetErrorsAndMessages();
        $this->questionnaireAnswers = [
            'dob_verification' => '',
            'father_name' => '',
            'mother_name' => '',
        ];
        $this->answeredQuestions = [
            'dob_verification' => false,
            'father_name' => false,
            'mother_name' => false,
        ];
        $this->questionResults = [
            'dob_verification' => null,
            'father_name' => null,
            'mother_name' => null,
        ];
        $this->currentQuestion = 1;
        $this->currentAnswer = '';
        $this->isProcessing = false;
        $this->showFinalResult = false;
        $this->correctAnswersCount = 0;
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
        $answeredCount = 0;
        foreach ($this->answeredQuestions as $answered) {
            if ($answered) {
                $answeredCount++;
            }
        }
        return ($answeredCount / $this->totalQuestions) * 100;
    }

    // Get current question text
    public function getCurrentQuestionText()
    {
        $questions = [
            'dob_verification' => 'Date of Birth',
            'father_name' => "Father's Full Name",
            'mother_name' => "Mother's Full Name",
        ];
        
        $questionKey = $this->getCurrentQuestionKey();
        return $questions[$questionKey] ?? '';
    }

    // Get current question placeholder
    public function getCurrentQuestionPlaceholder()
    {
        $placeholders = [
            'dob_verification' => 'Select your date of birth',
            'father_name' => "Enter father's full name",
            'mother_name' => "Enter mother's full name",
        ];
        
        $questionKey = $this->getCurrentQuestionKey();
        return $placeholders[$questionKey] ?? '';
    }

    // Get helper text for questions
    public function getQuestionHelper($questionKey)
    {
        $helpers = [
            'dob_verification' => 'Enter the exact date of birth as registered with NIDA',
            'father_name' => 'Enter your father\'s full name as registered with NIDA',
            'mother_name' => 'Enter your mother\'s full name as registered with NIDA',
        ];

        return $helpers[$questionKey] ?? '';
    }
}

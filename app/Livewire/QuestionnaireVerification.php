<?php

namespace App\Livewire;

use App\Exceptions\NidaException;
use App\Jobs\FetchCreditScore;
use App\Models\NidaVerification;
use App\Models\UserProfile;
use App\Services\NidaVerificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class QuestionnaireVerification extends Component
{
    /**
     * Current NIDA question.
     */
    public string $questionEn = '';

    public string $questionSw = '';

    /**
     * NIDA request/question code.
     *
     * This must be sent back to NIDA together with the answer.
     */
    public ?string $rqCode = null;

    /**
     * NIDA transaction ID.
     */
    public ?string $transactionId = null;

    /**
     * NIN being verified.
     */
    public ?string $nin = null;

    /**
     * Current answer entered by the user.
     */
    public string $currentAnswer = '';

    /**
     * Track questions asked during this verification session.
     *
     * We don't know in advance how many questions NIDA will ask,
     * so these are dynamic rather than fixed DOB/father/mother fields.
     */
    public array $questionHistory = [];

    /**
     * UI state.
     */
    public bool $isProcessing = false;

    public bool $isVerified = false;

    public bool $showFinalResult = false;

    public string $errorMessage = '';

    public string $successMessage = '';

    /**
     * Number of questions answered.
     */
    public int $answeredQuestions = 0;

    /**
     * NIDA service.
     *
     * Livewire services should be injected through boot(),
     * rather than relying on a constructor.
     */
    protected NidaVerificationService $nidaVerificationService;

    /**
     * Validation rules for the current answer.
     *
     * NIDA controls the actual question, so we cannot use
     * fixed validation rules such as DOB/father/mother.
     */
    protected function rules(): array
    {
        return [
            'currentAnswer' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    protected $messages = [
        'currentAnswer.required' => 'Please provide an answer.',
        'currentAnswer.string' => 'Please provide a valid answer.',
        'currentAnswer.max' => 'The answer is too long.',
    ];

    /**
     * Inject the NIDA service.
     */
    public function boot(NidaVerificationService $nidaVerificationService): void
    {
        $this->nidaVerificationService = $nidaVerificationService;
    }

    /**
     * Initialize the component.
     */
    public function mount(): void
    {
        $this->resetVerificationState();

        $user = Auth::user();

        if (!$user) {
            $this->errorMessage = 'You must be logged in to perform NIDA verification.';
            return;
        }

        /*
         * Determine the NIN to verify.
         *
         * For company users, use company_contact_nida when the
         * normal nida_number has not been populated.
         */
        $this->nin = $user->nida_number;

        if (
            empty($this->nin) &&
            $user->registration_type === 'company' &&
            !empty($user->company_contact_nida)
        ) {
            $this->nin = $user->company_contact_nida;
        }

        if (empty($this->nin)) {
            $this->errorMessage = 'No NIDA number is available for verification.';
            return;
        }

        /*
         * Check whether this user has already completed NIDA
         * questionnaire verification.
         */
        $existingVerification = NidaVerification::query()
            ->where('user_id', $user->id)
            ->where('verification_method', 'questionnaire')
            ->where('status', 'verified')
            ->first();

        if ($existingVerification) {
            $this->isVerified = true;

            $this->successMessage =
                $user->registration_type === 'company'
                    ? 'Your NIDA verification is complete! You can now continue with company document upload.'
                    : 'Your identity has already been successfully verified through NIDA.';

            return;
        }

        /*
         * Automatically start the NIDA KBA process.
         */
        $this->startVerification();
    }

    /**
     * Render component.
     */
    public function render()
    {
        return view('livewire.questionnaire-verification');
    }

    /**
     * Start NIDA Knowledge-Based Verification.
     *
     * This replaces the old fixed "Question 1" simulation.
     *
     * NIDA determines what the first question is.
     */
    public function startVerification(): void
    {
        if (empty($this->nin)) {
            $this->errorMessage = 'NIDA number is required for verification.';
            return;
        }

        $this->isProcessing = true;
        $this->errorMessage = '';
        $this->successMessage = '';
        $this->showFinalResult = false;

        try {
            $result = $this->nidaVerificationService
                ->getVerificationQuestion($this->nin);

            $this->handleNextQuestion($result);

            Log::channel('nida')->info('NIDA KBA verification started', [
                'user_id' => Auth::id(),
                'nin' => $this->nin,
                'rqCode' => $this->rqCode,
            ]);

        } catch (NidaException $e) {
            $this->handleNidaException($e);
        } catch (\Throwable $e) {
            Log::channel('nida')->error('NIDA KBA start verification error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            $this->errorMessage =
                'Unable to start NIDA verification. Please try again.';
        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Submit the answer to the current NIDA question.
     *
     * NIDA determines whether:
     *
     * 1. Another question should be presented, or
     * 2. Verification is complete.
     */
    public function submitCurrentQuestion(): void
    {
        if (empty($this->rqCode)) {
            $this->errorMessage =
                'The current verification question is invalid. Please restart verification.';

            return;
        }

        $this->validate();

        $this->isProcessing = true;
        $this->errorMessage = '';
        $this->successMessage = '';

        try {
            /*
             * Keep a local history for the current verification
             * session.
             *
             * This is useful if questionnaire_answers is being
             * stored in NidaVerification.
             */
            $this->questionHistory[] = [
                'rqCode' => $this->rqCode,
                'questionEn' => $this->questionEn,
                'questionSw' => $this->questionSw,
                'answer' => $this->currentAnswer,
                'answered_at' => now()->toDateTimeString(),
            ];

            $result = $this->nidaVerificationService
                ->submitVerificationAnswer(
                    $this->nin,
                    $this->rqCode,
                    $this->currentAnswer
                );

            $this->answeredQuestions++;

            /*
             * NIDA says there are more questions.
             */
            if ($result['type'] === 'NEXT_QUESTION') {
                $this->handleNextQuestion($result);

                return;
            }

            /*
             * NIDA says identity verification is complete.
             */
            if ($result['type'] === 'VERIFIED') {
                $this->processSuccessfulVerification(
                    $result['profile']
                );

                return;
            }

            /*
             * We should never reach here because the service
             * normalizes NIDA responses.
             */
            throw new \RuntimeException(
                'Unexpected NIDA verification result.'
            );

        } catch (NidaException $e) {
            $this->handleNidaException($e);

        } catch (\Throwable $e) {
            Log::channel('nida')->error('NIDA KBA answer error', [
                'user_id' => Auth::id(),
                'nin' => $this->nin,
                'rqCode' => $this->rqCode,
                'message' => $e->getMessage(),
            ]);

            $this->errorMessage =
                'Verification could not be completed. Please try again.';

        } finally {
            $this->isProcessing = false;
        }
    }

    /**
     * Process a NEXT_QUESTION response from NIDA.
     */
    private function handleNextQuestion(array $result): void
    {
        $this->rqCode = $result['rqCode'] ?? null;

        $this->transactionId = $result['transactionId'] ?? null;

        $this->questionEn = $result['questionEn'] ?? '';

        $this->questionSw = $result['questionSw'] ?? '';

        /*
         * Clear previous answer.
         */
        $this->currentAnswer = '';

        /*
         * Reset validation messages for the new question.
         */
        $this->resetValidation();

        $this->errorMessage = '';

        $this->showFinalResult = false;
    }

    /**
     * Process successful NIDA verification.
     *
     * At this point NIDA has returned the verified profile.
     */
    private function processSuccessfulVerification(array $profile): void
    {
        $user = Auth::user();

        if (!$user) {
            throw new \RuntimeException(
                'Authenticated user could not be found.'
            );
        }

        /*
         * Save the NIDA verification record.
         */
        $this->saveVerificationRecord($profile);

        /*
         * Update the user's verification status/profile.
         */
        $this->completeVerification($profile);

        $this->isVerified = true;

        $this->showFinalResult = true;

        $this->rqCode = null;

        $this->questionEn = '';

        $this->questionSw = '';

        $this->currentAnswer = '';

        $this->errorMessage = '';

        if ($user->registration_type === 'company') {
            $this->successMessage =
                'Your NIDA verification is complete! You can now continue with company document upload.';
        } else {
            $this->successMessage =
                'Your identity has been successfully verified through NIDA. Your profile has been updated. You can now complete your profile information.';
        }

        Log::channel('nida')->info('NIDA KBA verification completed', [
            'user_id' => $user->id,
            'nin' => $profile['nin'] ?? $this->nin,
        ]);
    }

    /**
     * Save NIDA verification record.
     *
     * IMPORTANT:
     * There is no match score anymore.
     *
     * NIDA itself determines whether the identity is verified.
     */
    private function saveVerificationRecord(array $profile): void
    {
        $user = Auth::user();

        /*
         * Prefer the NIN returned by NIDA.
         */
        $nidaNumber = $profile['nin'] ?? $this->nin;

        /*
         * Store the questions/answers that occurred during this
         * verification session.
         */
        $questionnaireAnswers = $this->questionHistory;

        /*
         * Store only the relevant normalized profile information.
         *
         * Do not unnecessarily duplicate photo/signature into
         * questionnaire_answers.
         */
        $verificationResponse = [
            'type' => 'VERIFIED',
            'profile' => [
                'nin' => $profile['nin'] ?? null,
                'firstName' => $profile['firstName'] ?? null,
                'middleName' => $profile['middleName'] ?? null,
                'lastName' => $profile['lastName'] ?? null,
                'otherName' => $profile['otherName'] ?? null,
                'dateOfBirth' => $profile['dateOfBirth'] ?? null,
                'sex' => $profile['sex'] ?? null,
                'nationality' => $profile['nationality'] ?? null,
                'photo' => $profile['photo'] ?? null,
                // 'placeOfBirth' => $profile['placeOfBirth'] ?? null,
                // 'residentRegion' => $profile['residentRegion'] ?? null,
                // 'residentDistrict' => $profile['residentDistrict'] ?? null,
                // 'residentWard' => $profile['residentWard'] ?? null,
                // 'residentVillage' => $profile['residentVillage'] ?? null,
                // 'residentStreet' => $profile['residentStreet'] ?? null,
                // 'residentPostalAddress' => $profile['residentPostalAddress'] ?? null,
                // 'residentPostCode' => $profile['residentPostCode'] ?? null,
                // 'birthCountry' => $profile['birthCountry'] ?? null,
                // 'birthRegion' => $profile['birthRegion'] ?? null,
                // 'birthDistrict' => $profile['birthDistrict'] ?? null,
                // 'birthWard' => $profile['birthWard'] ?? null,
            ],
        ];

        NidaVerification::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'nida_number' => $nidaNumber,

                'status' => 'verified',

                'verified_at' => now(),

                'verification_method' => 'questionnaire',

                'questionnaire_answers' => $questionnaireAnswers,

                'nida_response' => $verificationResponse,

                /*
                 * NIDA does not return a match score.
                 *
                 * Therefore do not create a fake score.
                 */
                'match_score' => null,

                'expires_at' => now()->addYears(5),
            ]
        );
    }

    /**
     * Complete the application's verification process.
     */
    private function completeVerification(array $profile): void
    {
        $user = Auth::user();

        /*
         * NIDA is the authoritative source for the identity data.
         *
         * Update the user with the normalized profile returned
         * after successful verification.
         */
        $updateData = [
            'nida_verified_at' => now(),
            'verification_status' => 'verified',
        ];

        /*
         * Use NIDA number returned by NIDA.
         */
        if (!empty($profile['nin'])) {
            $updateData['nida_number'] = $profile['nin'];
        } elseif (
            $user->registration_type === 'company' &&
            empty($user->nida_number) &&
            !empty($user->company_contact_nida)
        ) {
            $updateData['nida_number'] = $user->company_contact_nida;
        }

        /*
         * Update personal information from NIDA where available.
         *
         * Be careful with these field names if your users table
         * uses different names.
         */
        if (!empty($profile['firstName'])) {
            $updateData['first_name'] = $profile['firstName'] ?? '';
        }

        if (!empty($profile['middleName'])) {
            $updateData['middle_name'] = $profile['middleName'] ?? '';
        }

        if (!empty($profile['lastName'])) {
            $updateData['last_name'] = $profile['lastName'] ?? '';
        }

        if (!empty($profile['dateOfBirth'])) {
            $updateData['date_of_birth'] = $profile['dateOfBirth'];
        }

        if (!empty($profile['sex'])) {
            $updateData['gender'] = $profile['sex'];
        }

        if( !empty($profile['photo']) ) {
            $updateData['profile_photo_path'] = $this->processPhoto($profile['photo']);
        }

        $updateData['name'] = $updateData['first_name'] . ' ' . $updateData['middle_name'] . ' ' . $updateData['last_name'];

        $user->update($updateData);

        /*
         * Individual users get a user profile.
         *
         * Company KYC continues through company documents.
         */
        if ($user->registration_type !== 'company') {
            $this->createOrUpdateUserProfile($user, $profile);
        }

        /*
         * Fetch credit score after successful NIDA verification.
         */
        if (
            $user->registration_type === 'individual' &&
            !empty($user->nida_number)
        ) {
            FetchCreditScore::dispatch($user->id);

            Log::info(
                'QuestionnaireVerification: Dispatched FetchCreditScore job after NIDA verification',
                [
                    'user_id' => $user->id,
                ]
            );
        }
    }

    /**
     * Create/update the user profile using NIDA data.
     */
    private function createOrUpdateUserProfile(
        $user,
        array $profile
    ): void {
        try {
            $userProfile = UserProfile::firstOrNew([
                'user_id' => $user->id,
            ]);

            $profileData = [
                'first_name' => $profile['firstName']
                    ?? $user->first_name
                    ?? $user->name
                    ?? '',

                'last_name' => $profile['lastName']
                    ?? $user->last_name
                    ?? '',

                'national_id' => $profile['nin']
                    ?? $user->nida_number
                    ?? '',

                'email' => $user->email ?? '',

                'phone_number' => $user->phone ?? '',

                'last_updated' => now(),
            ];

            /*
             * NIDA date of birth should already be normalized
             * by the service.
             */
            if (!empty($profile['dateOfBirth'])) {
                try {
                    $profileData['date_of_birth'] =
                        \Carbon\Carbon::parse(
                            $profile['dateOfBirth']
                        )->format('Y-m-d');

                } catch (\Throwable $e) {
                    $profileData['date_of_birth'] = null;
                }
            } elseif ($user->date_of_birth) {
                try {
                    $profileData['date_of_birth'] =
                        \Carbon\Carbon::parse(
                            $user->date_of_birth
                        )->format('Y-m-d');

                } catch (\Throwable $e) {
                    $profileData['date_of_birth'] = null;
                }
            }

            if (!$userProfile->exists) {
                $profileData['user_id'] = $user->id;
            }

            $userProfile->fill($profileData);

            $userProfile->save();

            $userProfile->calculateCompletionPercentage();

        } catch (\Throwable $e) {
            /*
             * Profile creation failure should not undo the fact
             * that NIDA verification itself succeeded.
             */
            Log::error(
                'Error creating/updating user profile after NIDA verification',
                [
                    'user_id' => $user->id,
                    'message' => $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Handle NIDA-specific exceptions.
     */
    private function handleNidaException(
        NidaException $exception
    ): void {
        switch ($exception->errorCode) {
            case 'NIDA_VERIFICATION_FAILED':

                $this->showFinalResult = true;

                $this->errorMessage =
                    'Identity verification was not successful. Please check your answer and try again.';

                break;

            case 'NIDA_UNAVAILABLE':
            case 'NIDA_TOKEN_ERROR':

                $this->errorMessage =
                    'NIDA verification service is currently unavailable. Please try again later.';

                break;

            case 'NIDA_BAD_RESPONSE':

                $this->errorMessage =
                    'We received an unexpected response from NIDA. Please try again later.';

                break;

            default:

                $this->errorMessage =
                    'NIDA verification could not be completed. Please try again.';
        }

        Log::warning('NIDA verification exception', [
            'user_id' => Auth::id(),
            'nin' => $this->nin,
            'error_code' => $exception->errorCode,
            'nida_status_code' => $exception->nidaStatusCode,
            'message' => $exception->getMessage(),
        ]);
    }

    /**
     * Retry the entire NIDA KBA verification process.
     *
     * A retry starts a completely new NIDA verification transaction.
     */
    public function retryVerification(): void
    {
        $this->resetVerificationState();

        $user = Auth::user();

        if (!$user) {
            $this->errorMessage =
                'You must be logged in to perform NIDA verification.';
            return;
        }

        $this->nin = $user->nida_number;

        if (
            empty($this->nin) &&
            $user->registration_type === 'company' &&
            !empty($user->company_contact_nida)
        ) {
            $this->nin = $user->company_contact_nida;
        }

        $this->startVerification();
    }

    /**
     * Reset component verification state.
     */
    private function resetVerificationState(): void
    {
        $this->questionEn = '';

        $this->questionSw = '';

        $this->rqCode = null;

        $this->transactionId = null;

        $this->currentAnswer = '';

        $this->questionHistory = [];

        $this->isProcessing = false;

        $this->isVerified = false;

        $this->showFinalResult = false;

        $this->errorMessage = '';

        $this->successMessage = '';

        $this->answeredQuestions = 0;

        $this->resetValidation();
    }

    /**
     * Get current question text.
     *
     * Kept as a method so your existing Blade template can use it.
     */
    public function getCurrentQuestionText()
    {
        return $this->questionEn;
    }

    /**
     * Get Swahili question.
     */
    public function getCurrentQuestionTextSw()
    {
        return $this->questionSw;
    }

    /**
     * Get current question placeholder.
     */
    public function getCurrentQuestionPlaceholder()
    {
        return 'Enter your answer';
    }

    /**
     * Get helper text.
     */
    public function getQuestionHelper()
    {
        return 'Provide the answer exactly as registered with NIDA.';
    }

    /**
     * Progress indicator.
     *
     * We cannot calculate a percentage because NIDA does not
     * tell us how many questions will be asked.
     *
     * Return an answered-question count instead.
     */
    public function getProgressPercentage()
    {
        /*
         * No total is known from NIDA.
         *
         * Return 0 while verification is in progress rather
         * than presenting a fake percentage.
         */
        return 0;
    }

    /**
     * Process the uploaded photo.
     */
    private function processPhoto($photo)
    {
        // $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);

        // Decode
        $imageData = base64_decode($photo, true);

        if ($imageData === false) {
            throw new \Exception('Invalid Base64 image');
        }

        // Save to Laravel storage
        $filename = 'photo_' . time() . '.jpg';

        Storage::disk('public')->put($filename, $imageData);

        // $url = Storage::disk('public')->url($filename);

        return $filename;
    }

    /**
     * Number of questions currently answered.
     */
    public function getAnsweredQuestionsCount()
    {
        return $this->answeredQuestions;
    }

    /**
     * Navigate to profile after successful verification.
     */
    public function goToProfile()
    {
        $user = Auth::user();

        if ($user->registration_type === 'company') {
            return redirect()
                ->route('company.kyc')
                ->with(
                    'success',
                    'NIDA verification completed! Please continue with document upload.'
                );
        }

        return redirect()->route('loan-application.profile');
    }

    /**
     * Return to verification method selection.
     */
    public function backToMethodSelection()
    {
        return redirect()->route('verification.options');
    }

    /**
     * Redirect to dashboard.
     */
    public function redirectToDashboard()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Real-time validation.
     */
    public function updatedCurrentAnswer(): void
    {
        $this->validateOnly('currentAnswer');
    }
}


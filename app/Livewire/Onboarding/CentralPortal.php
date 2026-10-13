<?php

namespace App\Livewire\Onboarding;

use App\Models\NidaVerification;
use App\Models\Role;
use App\Models\User;
use App\Services\CentralUserApiClient;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * Unified borrower registration/login portal driven by the centralized
 * user API (see "centralization of users/Web_flow.pdf" and the
 * "CIT_APP APIs v5" Postman collection).
 *
 * State machine:
 *   phone -> otp -> (LOGIN -> login | NEW_USER -> nin -> nin_qa -> set_password
 *                     | ONBOARD_CHANNEL -> set_password | BLOCKED -> blocked)
 *   direct_login is the "already have web access" shortcut that skips OTP.
 */
class CentralPortal extends Component
{
    public string $step = 'phone';

    public string $phone = '';

    public string $otp = '';

    // register vs login, resolved from verify-phone's own response — see
    // CentralUserApiClient::resolveOtpPurpose(). Re-resolved on every
    // sendPhoneOtp() call (including resendOtp()) in case the phone's
    // registration state changed since the original request.
    public string $otpPurpose = 'registration';

    public ?string $nextStep = null;

    public ?string $centralUserId = null;

    public ?string $phoneProofToken = null;

    public string $password = '';

    public string $nin = '';

    public ?string $rqCode = null;

    public string $questionEn = '';

    public string $questionSw = '';

    public string $answer = '';

    public int $ninAttempts = 0;

    public ?string $preRegToken = null;

    public string $newPassword = '';

    public string $newPasswordConfirmation = '';

    public string $errorMessage = '';

    public string $infoMessage = '';

    protected CentralUserApiClient $api;

    public function boot(CentralUserApiClient $api): void
    {
        $this->api = $api;

        if (session()->has('locale')) {
            $locale = session()->get('locale');
            if (in_array($locale, ['en', 'sw'])) {
                app()->setLocale($locale);
            }
        }
    }

    /**
     * Supports the /login handoff: a phone number typed into the main
     * login form that isn't a local (admin/lender) account is redirected
     * here as ?phone=..., so the visitor doesn't have to retype it — jump
     * straight to requesting the OTP instead of showing the phone step.
     */
    public function mount(): void
    {
        $phone = request()->query('phone');

        if ($phone) {
            $this->phone = $this->normalizePhone((string) $phone);
            $this->sendPhoneOtp();
        }
    }

    public function render()
    {
        return view('livewire.onboarding.central-portal')
            ->extends('layouts.auth-split', ['imageKey' => 'central_portal'])
            ->title(__('auth.login_or_register'));
    }

    protected function resetMessages(): void
    {
        $this->errorMessage = '';
        $this->infoMessage = '';
    }

    /**
     * Record a portal activity event. Phone numbers are logged in full
     * here (they are not secrets), but OTPs/passwords/answers never are.
     */
    protected function logActivity(string $event, array $context = []): void
    {
        Log::channel('central_user_api')->info($event, array_merge([
            'phone' => $this->phone,
            'step' => $this->step,
            'central_user_id' => $this->centralUserId,
            'local_user_id' => Auth::id(),
            'session_id' => session()->getId(),
        ], $context));
    }

    protected function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = '255'.substr($digits, 1);
        }

        return $digits;
    }

    protected function phoneRules(): array
    {
        return ['required', 'regex:/^255[67]\d{8}$/'];
    }

    // ---------------------------------------------------------------
    // Portal entry: "New, or first time on web" (phone + OTP) path
    // ---------------------------------------------------------------

    public function sendPhoneOtp(): void
    {
        $this->resetMessages();
        $this->phone = $this->normalizePhone($this->phone);

        $this->validate([
            'phone' => $this->phoneRules(),
        ], [
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'Please enter a valid Tanzanian phone number.',
        ]);

        $result = $this->api->verifyPhone($this->phone, 'web');

        if (! $result['ok']) {
            $this->errorMessage = $this->extractMessage($result, 'Unable to send the verification code. Please try again.');
            $this->logActivity('otp_request_failed', [
                'status' => $result['status'],
                'error_code' => $result['body']['errorCode'] ?? null,
            ]);

            return;
        }

        $this->otp = '';
        $this->otpPurpose = $this->api->resolveOtpPurpose($result);
        $this->step = 'otp';
        $this->infoMessage = $result['body']['message'] ?? 'A verification code has been sent to your phone.';
        $this->logActivity('otp_requested', ['otp_purpose' => $this->otpPurpose]);
    }

    public function resendOtp(): void
    {
        $this->sendPhoneOtp();
    }

    public function verifyOtp(): void
    {
        $this->resetMessages();

        $this->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Please enter the verification code.',
            'otp.digits' => 'The verification code must be 6 digits.',
        ]);

        $result = $this->api->verifyOtp($this->phone, $this->otp, $this->otpPurpose, 'web');

        if (! $result['ok']) {
            $this->errorMessage = $this->extractMessage($result, 'The code is invalid or expired. Please request a new one.');
            $this->logActivity('otp_verify_failed', [
                'status' => $result['status'],
                'error_code' => $result['body']['errorCode'] ?? null,
            ]);

            return;
        }

        $body = $result['body'];
        $this->nextStep = $body['nextStep'] ?? null;
        $this->centralUserId = $body['userId'] ?? null;
        $this->phoneProofToken = $body['phoneProofToken'] ?? null;

        switch ($this->nextStep) {
            case 'LOGIN':
                $this->step = 'login';
                break;
            case 'NEW_USER':
                $this->step = 'nin';
                break;
            case 'ONBOARD_CHANNEL':
                $this->step = 'set_password';
                break;
            case 'BLOCKED':
                $this->step = 'blocked';
                $this->infoMessage = 'Your account access is currently blocked. Please contact support.';
                break;
            default:
                $this->errorMessage = 'Unexpected response from the identity service. Please try again.';
        }

        $this->logActivity('otp_verified', ['next_step' => $this->nextStep]);
    }

    // ---------------------------------------------------------------
    // "Has web access" shortcut: direct phone + password login
    // ---------------------------------------------------------------

    public function switchToDirectLogin(): void
    {
        $this->reset(['otp', 'nextStep', 'phoneProofToken', 'errorMessage', 'infoMessage', 'password']);
        $this->step = 'direct_login';
    }

    public function switchToPhoneEntry(): void
    {
        $this->reset();
        $this->step = 'phone';
    }

    public function directLogin(): void
    {
        $this->resetMessages();
        $this->phone = $this->normalizePhone($this->phone);

        $this->validate([
            'phone' => $this->phoneRules(),
            'password' => ['required', 'string'],
        ], [
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'Please enter a valid Tanzanian phone number.',
            'password.required' => 'Please enter your password.',
        ]);

        $this->loginWithPassword();
    }

    public function loginWithPassword(): void
    {
        $this->resetMessages();

        $this->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Please enter your password.',
        ]);

        $result = $this->api->loginWeb($this->phone, $this->password);

        if (! $result['ok']) {
            $this->errorMessage = $this->extractMessage($result, 'Incorrect phone number or password.');
            $this->logActivity('password_login_failed', [
                'status' => $result['status'],
                'error_code' => $result['body']['errorCode'] ?? null,
            ]);

            return;
        }

        $this->logActivity('password_login_succeeded');
        $this->completeLogin($result['body']);
    }

    // ---------------------------------------------------------------
    // NEW_USER branch: NIN entry + Q&A verification (web = no biometric)
    // ---------------------------------------------------------------

    public function startNinVerification(): void
    {
        $this->resetMessages();

        $this->validate([
            'nin' => ['required', 'digits:20'],
        ], [
            'nin.required' => 'Please enter your NIDA number (NIN).',
            'nin.digits' => 'NIN must be exactly 20 digits.',
        ]);

        $result = $this->api->startNinQa($this->nin, 'web');

        if (! $result['ok']) {
            $errorCode = $result['body']['errorCode'] ?? null;

            if ($errorCode === 'NIN_ALREADY_REGISTERED') {
                $this->step = 'blocked';
                $this->infoMessage = $this->extractMessage($result, 'This identity is already registered. Please contact support.');
                $this->logActivity('nin_already_registered');

                return;
            }

            $this->errorMessage = $this->extractMessage($result, 'Unable to start identity verification. Please try again.');
            $this->logActivity('nin_start_failed', ['status' => $result['status'], 'error_code' => $errorCode]);

            return;
        }

        $body = $result['body'];
        $this->rqCode = $body['rqCode'] ?? null;
        $this->questionEn = $body['question']['en'] ?? '';
        $this->questionSw = $body['question']['sw'] ?? '';
        $this->answer = '';
        $this->ninAttempts = 0;
        $this->step = 'nin_qa';
        $this->logActivity('nin_qa_started');
    }

    public function submitNinAnswer(): void
    {
        $this->resetMessages();

        $this->validate([
            'answer' => ['required', 'string', 'max:255'],
        ], [
            'answer.required' => 'Please provide an answer.',
        ]);

        $result = $this->api->submitNinQaAnswer($this->nin, (string) $this->rqCode, $this->answer);

        if (! $result['ok']) {
            $this->ninAttempts++;
            $this->logActivity('nin_answer_rejected', [
                'attempt' => $this->ninAttempts,
                'error_code' => $result['body']['errorCode'] ?? null,
            ]);

            if ($this->ninAttempts >= 3) {
                $this->step = 'blocked';
                $this->infoMessage = 'Identity verification was not successful. Please contact support.';
                $this->logActivity('nin_qa_blocked');

                return;
            }

            $this->answer = '';
            $this->errorMessage = $this->extractMessage($result, 'That answer was not correct. Please try again.');

            return;
        }

        $body = $result['body'];

        if (($body['complete'] ?? false) === true || ! empty($body['preRegistrationToken'])) {
            $this->preRegToken = $body['preRegistrationToken'] ?? null;
            $this->step = 'set_password';
            $this->infoMessage = 'Identity verified. Please set your password to finish creating your account.';
            $this->logActivity('nin_qa_completed', ['attempts' => $this->ninAttempts + 1]);

            return;
        }

        $this->rqCode = $body['rqCode'] ?? null;
        $this->questionEn = $body['question']['en'] ?? '';
        $this->questionSw = $body['question']['sw'] ?? '';
        $this->answer = '';
        $this->logActivity('nin_qa_next_question');
    }

    // ---------------------------------------------------------------
    // Password-setting step, shared by NEW_USER and ONBOARD_CHANNEL
    // ---------------------------------------------------------------

    public function setPassword(): void
    {
        $this->resetMessages();

        $this->validate([
            'newPassword' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'same:newPasswordConfirmation',
            ],
            'newPasswordConfirmation' => ['required'],
        ], [
            'newPassword.required' => 'Please enter a password.',
            'newPassword.min' => 'Password must be at least 8 characters.',
            'newPassword.regex' => 'Password must include an uppercase letter, a lowercase letter, and a number.',
            'newPassword.same' => 'Password confirmation does not match.',
            'newPasswordConfirmation.required' => 'Please confirm your password.',
        ]);

        if ($this->nextStep === 'NEW_USER') {
            $this->completeNewUserRegistration();

            return;
        }

        if ($this->nextStep === 'ONBOARD_CHANNEL') {
            $this->completeChannelOnboarding();

            return;
        }

        $this->errorMessage = 'Something went wrong. Please start again.';
        $this->step = 'phone';
    }

    protected function completeNewUserRegistration(): void
    {
        if (! $this->phoneProofToken || ! $this->preRegToken) {
            $this->errorMessage = 'Your verification session has expired. Please start again.';
            $this->step = 'phone';

            return;
        }

        $result = $this->api->register($this->phone, $this->newPassword, $this->phoneProofToken, $this->preRegToken);

        if (! $result['ok']) {
            $this->errorMessage = $this->extractMessage($result, 'We could not complete your registration. Please try again.');
            $this->logActivity('registration_failed', [
                'status' => $result['status'],
                'error_code' => $result['body']['errorCode'] ?? null,
            ]);

            return;
        }

        $this->logActivity('registration_succeeded');
        $this->completeLogin($result['body'], ninVerified: true);
    }

    protected function completeChannelOnboarding(): void
    {
        if (! $this->phoneProofToken) {
            $this->errorMessage = 'Your verification session has expired. Please start again.';
            $this->step = 'phone';

            return;
        }

        $onboardResult = $this->api->onboardWebChannel($this->phone, $this->newPassword, $this->phoneProofToken);

        if (! $onboardResult['ok']) {
            if (($onboardResult['body']['errorCode'] ?? null) === 'CHANNEL_ALREADY_ONBOARDED') {
                $this->step = 'login';
                $this->infoMessage = 'This account already has web access. Please log in with your password.';
                $this->logActivity('channel_already_onboarded');

                return;
            }

            $this->errorMessage = $this->extractMessage($onboardResult, 'We could not set up web access for this account. Please try again.');
            $this->logActivity('channel_onboarding_failed', [
                'status' => $onboardResult['status'],
                'error_code' => $onboardResult['body']['errorCode'] ?? null,
            ]);

            return;
        }

        $this->logActivity('channel_onboarded');

        $loginResult = $this->api->loginWeb($this->phone, $this->newPassword);

        if (! $loginResult['ok']) {
            $this->step = 'login';
            $this->infoMessage = 'Web access created. Please log in with your new password.';
            $this->logActivity('post_onboarding_login_failed', ['status' => $loginResult['status']]);

            return;
        }

        $this->completeLogin($loginResult['body']);
    }

    // ---------------------------------------------------------------
    // Shared completion: bridge to the local user and establish a session
    // ---------------------------------------------------------------

    protected function completeLogin(array $body, bool $ninVerified = false): void
    {
        $centralUser = $body['user'] ?? [];
        $status = $centralUser['status'] ?? 'active';

        if ($status !== 'active') {
            $this->step = 'blocked';
            $this->infoMessage = 'Your account is not active. Please contact support.';
            $this->logActivity('account_inactive', ['central_user_id' => $centralUser['id'] ?? $this->centralUserId]);

            return;
        }

        $token = $this->extractToken($body);
        $wasExistingLocalUser = User::where('phone', $centralUser['phone_number'] ?? $this->phone)->exists();
        $localUser = $this->bridgeLocalUser($centralUser, $ninVerified, $token);

        Auth::login($localUser, true);
        Session::regenerate();
        Session::put('otp_verified', true);

        if ($token) {
            Session::put('central_access_token', $token);
        }

        $this->logActivity('session_established', [
            'local_user_id' => $localUser->id,
            'local_user_created' => ! $wasExistingLocalUser,
            'nida_verified' => $ninVerified,
        ]);

        // Distinct from the "user_login" SystemLog row Auth::login() triggers
        // via HandleUserLogin — that fires on every login regardless of
        // cause; this one specifically marks "a brand-new local account was
        // bridged from the central user API just now," useful when
        // troubleshooting a live walkthrough (mirrors CLA's
        // consumer_registration.completed_via_cit_app audit entry).
        if (! $wasExistingLocalUser) {
            LogService::log(
                'borrower_registration.completed_via_central_api',
                "Borrower {$localUser->phone} registered via the central user API",
                'medium',
                $localUser,
                null,
                null,
                ['central_user_id' => $localUser->central_user_id, 'nida_verified' => $ninVerified]
            );
        }

        $this->redirect(route('dashboard'));
    }

    protected function extractToken(array $body): ?string
    {
        $token = $body['token'] ?? null;

        return $token ? preg_replace('/^Bearer\s+/i', '', $token) : null;
    }

    protected function bridgeLocalUser(array $centralUser, bool $ninVerified, ?string $token): User
    {
        $phone = $centralUser['phone_number'] ?? $this->phone;
        $centralUserId = $centralUser['id'] ?? $this->centralUserId;

        $user = User::where('phone', $phone)->first();

        if (! $user && $centralUserId) {
            $user = User::where('central_user_id', $centralUserId)->first();
        }

        if (! $user) {
            $user = DB::transaction(function () use ($phone, $centralUserId, $ninVerified) {
                $newUser = User::create([
                    'name' => 'Borrower',
                    'email' => $phone.'@phone.leadgenerator.local',
                    'phone' => $phone,
                    'central_user_id' => $centralUserId,
                    'nida_number' => $ninVerified ? $this->nin : null,
                    'password' => Hash::make(Str::random(40)),
                    'phone_verified_at' => now(),
                    'nida_verified_at' => $ninVerified ? now() : null,
                    'verification_status' => $ninVerified ? 'verified' : 'pending',
                    'registration_type' => 'individual',
                    'role' => 'borrower',
                ]);

                $borrowerRole = Role::where('name', 'borrower')->first();
                if ($borrowerRole) {
                    $newUser->assignRole($borrowerRole);
                }

                return $newUser;
            });
        } else {
            $updates = [];

            if ($centralUserId && ! $user->central_user_id) {
                $updates['central_user_id'] = $centralUserId;
            }

            if (! $user->phone_verified_at) {
                $updates['phone_verified_at'] = now();
            }

            if ($ninVerified && ! $user->isNidaVerified()) {
                $updates['nida_verified_at'] = now();
                $updates['verification_status'] = 'verified';
                $updates['nida_number'] = $this->nin;
            }

            if ($updates) {
                $user->update($updates);
            }
        }

        if ($ninVerified) {
            $this->saveLocalNidaVerification($user);
        }

        if ($token) {
            $this->syncProfile($user, $token);
        }

        return $user->fresh();
    }

    protected function saveLocalNidaVerification(User $user): void
    {
        NidaVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nida_number' => $this->nin,
                'status' => 'verified',
                'verified_at' => now(),
                'verification_method' => 'questionnaire',
                'expires_at' => now()->addYears(5),
            ]
        );
    }

    protected function syncProfile(User $user, string $token): void
    {
        try {
            $result = $this->api->getProfile($token);

            if (! $result['ok']) {
                return;
            }

            $profile = $result['body'];
            $updates = [];

            if (! empty($profile['firstName']) && $user->name === 'Borrower') {
                $updates['name'] = $profile['firstName'];
                $updates['first_name'] = $profile['firstName'];
            }

            if (! empty($profile['nin']) && empty($user->nida_number)) {
                $updates['nida_number'] = $profile['nin'];
                $updates['nida_verified_at'] = $user->nida_verified_at ?? now();
                $updates['verification_status'] = 'verified';
            }

            // NIN itself encodes date of birth and gender — e.g.
            // "19900101-12345-12345-21" is dob 1990-01-01, gender digit "2"
            // (1 = female, 2 = male). Derive both from whichever NIN we have
            // (just-synced above, or already on the user) rather than relying
            // on the profile endpoint to supply them separately.
            $nin = $profile['nin'] ?? $user->nida_number;
            $ninDetails = $nin ? $this->parseNinDetails($nin) : null;

            if ($ninDetails && $ninDetails['date_of_birth'] && empty($user->date_of_birth)) {
                $updates['date_of_birth'] = $ninDetails['date_of_birth'];
            }

            if ($updates) {
                $user->update($updates);
            }

            if ($ninDetails && $ninDetails['gender']) {
                $existingProfile = $user->profile;

                if (! $existingProfile || empty($existingProfile->gender)) {
                    $user->profile()->updateOrCreate([], [
                        'gender' => $ninDetails['gender'],
                        'date_of_birth' => $ninDetails['date_of_birth'] ?? $existingProfile?->date_of_birth,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to sync profile from central user API', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Tanzanian NIN is a 20-digit string shaped YYYYMMDD-XXXXX-XXXXX-GX:
     * the first 8 digits are the date of birth, and of the last two digits
     * the first (position 19 of 20) is the gender marker — "1" female,
     * "2" male. Returns nulls for whichever part can't be derived rather
     * than failing outright, since callers only act on the parts present.
     */
    protected function parseNinDetails(string $nin): array
    {
        $digits = preg_replace('/[^0-9]/', '', $nin) ?? '';
        $result = ['date_of_birth' => null, 'gender' => null];

        if (strlen($digits) !== 20) {
            return $result;
        }

        $dobDigits = substr($digits, 0, 8);

        try {
            $dob = Carbon::createFromFormat('Ymd', $dobDigits);

            if ($dob && $dob->format('Ymd') === $dobDigits) {
                $result['date_of_birth'] = $dob->format('Y-m-d');
            }
        } catch (\Throwable $e) {
            // Not a real calendar date (e.g. rolled-over month/day) — leave null.
        }

        $result['gender'] = match ($digits[18]) {
            '1' => 'female',
            '2' => 'male',
            default => null,
        };

        return $result;
    }

    protected function extractMessage(array $result, string $fallback): string
    {
        $body = $result['body'];

        if (! empty($body['errors']) && is_array($body['errors'])) {
            $first = $body['errors'][0];
            if (! empty($first['message'])) {
                return $first['message'];
            }
        }

        return $body['message'] ?? $fallback;
    }

    public function backToStart(): void
    {
        $this->reset();
        $this->step = 'phone';
    }
}

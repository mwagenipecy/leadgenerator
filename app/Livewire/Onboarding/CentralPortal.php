<?php

namespace App\Livewire\Onboarding;

use App\Models\NidaVerification;
use App\Models\Role;
use App\Models\User;
use App\Services\CentralUserApiClient;
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

    public function render()
    {
        return view('livewire.onboarding.central-portal');
    }

    protected function resetMessages(): void
    {
        $this->errorMessage = '';
        $this->infoMessage = '';
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

            return;
        }

        $this->otp = '';
        $this->step = 'otp';
        $this->infoMessage = $result['body']['message'] ?? 'A verification code has been sent to your phone.';
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

        $result = $this->api->verifyOtp($this->phone, $this->otp, 'login', 'web');

        if (! $result['ok']) {
            $this->errorMessage = $this->extractMessage($result, 'The code is invalid or expired. Please request a new one.');

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

            return;
        }

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
            if (($result['body']['errorCode'] ?? null) === 'NIN_ALREADY_REGISTERED') {
                $this->step = 'blocked';
                $this->infoMessage = $this->extractMessage($result, 'This identity is already registered. Please contact support.');

                return;
            }

            $this->errorMessage = $this->extractMessage($result, 'Unable to start identity verification. Please try again.');

            return;
        }

        $body = $result['body'];
        $this->rqCode = $body['rqCode'] ?? null;
        $this->questionEn = $body['question']['en'] ?? '';
        $this->questionSw = $body['question']['sw'] ?? '';
        $this->answer = '';
        $this->ninAttempts = 0;
        $this->step = 'nin_qa';
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

            if ($this->ninAttempts >= 3) {
                $this->step = 'blocked';
                $this->infoMessage = 'Identity verification was not successful. Please contact support.';

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

            return;
        }

        $this->rqCode = $body['rqCode'] ?? null;
        $this->questionEn = $body['question']['en'] ?? '';
        $this->questionSw = $body['question']['sw'] ?? '';
        $this->answer = '';
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

            return;
        }

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

                return;
            }

            $this->errorMessage = $this->extractMessage($onboardResult, 'We could not set up web access for this account. Please try again.');

            return;
        }

        $loginResult = $this->api->loginWeb($this->phone, $this->newPassword);

        if (! $loginResult['ok']) {
            $this->step = 'login';
            $this->infoMessage = 'Web access created. Please log in with your new password.';

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

            return;
        }

        $token = $this->extractToken($body);
        $localUser = $this->bridgeLocalUser($centralUser, $ninVerified, $token);

        Auth::login($localUser, true);
        Session::regenerate();
        Session::put('otp_verified', true);

        if ($token) {
            Session::put('central_access_token', $token);
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

            if ($updates) {
                $user->update($updates);
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to sync profile from central user API', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);
        }
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

<?php

namespace App\Livewire\Profile;

use App\Services\CentralUserApiClient;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

/**
 * Lets a borrower add or change the email on their central (CIT_APP)
 * identity from their profile page: register-email -> verify-email -> the
 * generic verify-otp confirm step (identifierType "email"), per
 * "centralization of users/CIT_APP APIs v5.postman_collection.json". There
 * is no dedicated "submit email otp" endpoint — confirmation reuses
 * verify-otp the same way phone OTP confirmation does in CentralPortal.
 *
 * Scoped to borrowers who already have a central_user_id (onboarded via
 * CentralPortal) and a live central_access_token in session — the central
 * user API has no token-refresh endpoint, so a borrower whose session token
 * has expired is asked to log in again via /onboarding/central rather than
 * silently falling back to a locally-only, unverified email.
 */
class EmailVerification extends Component
{
    public string $step = 'idle'; // idle, otp_pending

    public string $email = '';

    public string $otp = '';

    public string $errorMessage = '';

    public string $infoMessage = '';

    protected CentralUserApiClient $api;

    public function boot(CentralUserApiClient $api): void
    {
        $this->api = $api;
    }

    public function mount(): void
    {
        $this->email = Auth::user()->email ?? '';
    }

    protected function resetMessages(): void
    {
        $this->errorMessage = '';
        $this->infoMessage = '';
    }

    protected function bearerToken(): ?string
    {
        return Session::get('central_access_token');
    }

    public function submitEmail(): void
    {
        $this->resetMessages();

        $user = Auth::user();

        if (! $user->central_user_id) {
            $this->errorMessage = 'Your account is not yet linked to the central identity system, so email changes aren\'t verified here yet.';

            return;
        }

        $token = $this->bearerToken();

        if (! $token) {
            $this->errorMessage = 'Your verification session has expired. Please log in again to refresh it before changing your email.';

            return;
        }

        $this->email = trim(strtolower($this->email));

        $this->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        if ($this->email === $user->email && $user->email_verified_at) {
            $this->infoMessage = 'This email is already verified.';

            return;
        }

        $registerResult = $this->api->registerEmail($this->email, $user->central_user_id, $token);
        $registerErrorCode = $registerResult['body']['errorCode'] ?? null;

        // EMAIL_EXISTS here just means this email is already registered to
        // *this* account (e.g. a retry after the OTP step failed) — fine to
        // proceed straight to verification.
        if (! $registerResult['ok'] && $registerErrorCode !== 'EMAIL_EXISTS') {
            $this->errorMessage = $this->extractMessage($registerResult, 'Unable to register this email. Please try again.');
            $this->logActivity('email_register_failed', ['status' => $registerResult['status'], 'error_code' => $registerErrorCode]);

            return;
        }

        $verifyResult = $this->api->verifyEmail($this->email, $user->central_user_id, $token);

        if (! $verifyResult['ok']) {
            $this->errorMessage = $this->extractMessage($verifyResult, 'Unable to send a verification code to this email. Please try again.');
            $this->logActivity('email_verify_request_failed', ['status' => $verifyResult['status']]);

            return;
        }

        $this->otp = '';
        $this->step = 'otp_pending';
        $this->infoMessage = $verifyResult['body']['message'] ?? 'A verification code has been sent to this email.';
        $this->logActivity('email_otp_requested');
    }

    public function resendCode(): void
    {
        $this->submitEmail();
    }

    public function confirmOtp(): void
    {
        $this->resetMessages();

        $this->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $token = $this->bearerToken();

        if (! $token) {
            $this->errorMessage = 'Your verification session has expired. Please log in again to refresh it before changing your email.';
            $this->step = 'idle';

            return;
        }

        $result = $this->api->verifyOtp($this->email, $this->otp, 'email_verification', 'web', 'email');

        if (! $result['ok']) {
            $this->errorMessage = $this->extractMessage($result, 'That code is invalid or expired. Please try again.');
            $this->logActivity('email_otp_rejected');

            return;
        }

        $user = Auth::user();
        $user->forceFill([
            'email' => $this->email,
            'email_verified_at' => now(),
        ])->save();

        if ($user->profile) {
            $user->profile->update(['email' => $this->email]);
        }

        LogService::log(
            'borrower_email.verified_via_central_api',
            "Borrower {$user->phone} verified email {$this->email} via the central user API",
            'medium',
            $user
        );

        $this->step = 'idle';
        $this->otp = '';
        $this->infoMessage = 'Email verified and updated successfully.';
        $this->logActivity('email_verified');
    }

    public function cancel(): void
    {
        $this->step = 'idle';
        $this->otp = '';
        $this->resetMessages();
        $this->email = Auth::user()->email ?? '';
    }

    protected function logActivity(string $event, array $context = []): void
    {
        \Illuminate\Support\Facades\Log::channel('central_user_api')->info($event, array_merge([
            'local_user_id' => Auth::id(),
            'email' => $this->email,
        ], $context));
    }

    protected function extractMessage(array $result, string $fallback): string
    {
        $body = $result['body'] ?? [];

        if (! empty($body['errors']) && is_array($body['errors'])) {
            $first = $body['errors'][0];
            if (! empty($first['message'])) {
                return $first['message'];
            }
        }

        return $body['message'] ?? $fallback;
    }

    public function render()
    {
        return view('livewire.profile.email-verification');
    }
}

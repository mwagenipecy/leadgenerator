<?php

namespace App\Services;

use App\Models\User;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\SelcomSmsService;
use Carbon\Carbon;
use Exception;

class SmsOtpService
{
    /**
     * OTP expiry time in minutes
     */
    const OTP_EXPIRY_MINUTES = 10;

    /**
     * @var SelcomSmsService
     */
    protected SelcomSmsService $smsService;

    /**
     * SmsOtpService constructor.
     */
    public function __construct(SelcomSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Generate and send OTP to user via SMS.
     */
    public function generateAndSendOtp(User $user): bool
    {
        try {
            Log::info('Starting SMS OTP generation', [
                'user_id' => $user->id,
            ]);

            // Invalidate any existing OTPs for this user
            $this->invalidateExistingOtps($user);

            // Generate new OTP
            $otpCode = OtpCode::generateOtp();

            // Create OTP record
            $otp = OtpCode::create([
                'user_id' => $user->id,
                'otp_hash' => Hash::make($otpCode),
                'expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
                'is_used' => false,
            ]);

            Log::info('SMS OTP record created', [
                'user_id' => $user->id,
                'expires_at' => $otp->expires_at,
            ]);

            $phoneNumber = $user->phone;

            if (empty($phoneNumber)) {
                Log::error('Cannot send SMS OTP: user has no phone number', [
                    'user_id' => $user->id,
                ]);

                return false;
            }

            // Send OTP directly through the configured SMS provider
            try {
                $smsResponse = $this->smsService->sendOtp(
                    $phoneNumber,
                    $otpCode,
                    $user->name ?? null,
                    $user->id
                );

                Log::info('SMS OTP provider response', [
                    'user_id' => $user->id,
                    'phone' => $phoneNumber,
                    'response' => $smsResponse,
                ]);

                if (!($smsResponse['success'] ?? false)) {
                    Log::error('SMS OTP provider failed', [
                        'user_id' => $user->id,
                        'phone' => $phoneNumber,
                        'response' => $smsResponse,
                    ]);

                    return false;
                }

                Log::info('SMS OTP sent successfully', [
                    'user_id' => $user->id,
                    'phone' => $phoneNumber,
                ]);

            } catch (Exception $smsException) {
                Log::error('Failed to send SMS OTP', [
                    'user_id' => $user->id,
                    'phone' => $phoneNumber,
                    'error' => $smsException->getMessage(),
                ]);

                return false;
            }

            return true;

        } catch (Exception $e) {
            Log::error('Failed to generate SMS OTP', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Verify OTP.
     */
    public function verifyOtp(User $user, string $otpCode): bool
    {
        Log::info('Starting SMS OTP verification', [
            'user_id' => $user->id,
        ]);

        $otp = OtpCode::where('user_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->orderByDesc('id')
            ->first();

        if (!$otp || !Hash::check($otpCode, $otp->otp_hash)) {
            Log::warning('SMS OTP verification failed', [
                'user_id' => $user->id,
            ]);

            return false;
        }

        //update user's phone_verified_at timestamp
        User::where('id', $user->id)->update([
            'phone_verified_at' => Carbon::now(),
        ]);

        // Mark OTP as used
        $otp->markAsUsed();

        // Invalidate any other active OTPs for this user
        OtpCode::where('user_id', $user->id)
            ->where('is_used', false)
            ->update([
                'is_used' => true,
            ]);

        Log::info('SMS OTP verified successfully', [
            'user_id' => $user->id,
            'otp_id' => $otp->id,
        ]);

        return true;
    }

    /**
     * Check if user has a valid OTP.
     */
    public function hasValidOtp(User $user): bool
    {
        return $user->hasValidOtp();
    }

    /**
     * Get remaining time for OTP expiry in seconds.
     */
    public function getOtpRemainingTime(User $user): ?int
    {
        $otp = $user->latestValidOtp();

        if (!$otp) {
            return null;
        }

        $remainingSeconds = Carbon::now()
            ->diffInSeconds($otp->expires_at, false);

        return $remainingSeconds > 0
            ? $remainingSeconds
            : 0;
    }

    /**
     * Check if user can request a new OTP.
     *
     * Allow resend only if less than 1 minute remains
     * or no valid OTP exists.
     */
    public function canResendOtp(User $user): bool
    {
        $remainingTime = $this->getOtpRemainingTime($user);

        return $remainingTime === null || $remainingTime < 60;
    }

    /**
     * Invalidate all existing OTPs for a user.
     */
    private function invalidateExistingOtps(User $user): void
    {
        $updated = OtpCode::where('user_id', $user->id)
            ->where('is_used', false)
            ->update([
                'is_used' => true,
            ]);

        Log::info('Invalidated existing SMS OTPs', [
            'user_id' => $user->id,
            'count' => $updated,
        ]);
    }

    /**
     * Clean up expired OTPs.
     *
     * Can be called from a scheduled job.
     */
    public function cleanupExpiredOtps(): int
    {
        return OtpCode::where(
            'expires_at',
            '<',
            Carbon::now()
        )->delete();
    }
}


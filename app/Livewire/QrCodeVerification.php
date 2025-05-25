<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NidaVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QrCodeVerification extends Component
{
    public $verificationToken = '';
    public $sessionCode = '';
    public $qrCodeGenerated = false;
    public $phoneConnected = false;
    public $waitingForVerification = false;
    public $isVerified = false;
    public $errorMessage = '';
    public $successMessage = '';
    public $verificationStep = 'generate'; // 'generate', 'waiting', 'connected', 'complete'
    public $timeRemaining = 600; // 10 minutes in seconds
    public $pollCount = 0;
    public $maxPollAttempts = 120; // 10 minutes at 5-second intervals

    public function mount()
    {
        $this->generateQRCode();
    }

    public function render()
    {
        return view('livewire.qr-code-verification');
    }

    public function generateQRCode()
    {
        try {
            // Generate unique verification token and session code
            $this->verificationToken = Str::random(32);
            $this->sessionCode = strtoupper(Str::random(6));
            
            // Create or update verification record
            $verification = NidaVerification::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'nida_number' => Auth::user()->nida_number,
                    'verification_token' => $this->verificationToken,
                    'token_expires_at' => now()->addMinutes(10),
                    'verification_method' => 'qr_code',
                    'status' => 'pending',
                    'phone_connected' => false,
                    'expires_at' => now()->addHours(24)
                ]
            );

            $this->qrCodeGenerated = true;
            $this->verificationStep = 'waiting';
            $this->errorMessage = '';
            
            // Start polling for connection
            $this->dispatch('start-polling');
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to generate QR code. Please try again.';
            \Log::error('QR code generation error: ' . $e->getMessage());
        }
    }

    public function checkConnection()
    {
        try {
            $verification = NidaVerification::where('user_id', Auth::id())
                ->where('verification_token', $this->verificationToken)
                ->first();

            if (!$verification) {
                $this->errorMessage = 'Verification session not found.';
                return;
            }

            // Check if token expired
            if ($verification->isTokenExpired()) {
                $this->errorMessage = 'QR code expired. Please generate a new code.';
                $this->qrCodeGenerated = false;
                $this->verificationStep = 'generate';
                return;
            }

            // Check if phone connected
            if ($verification->phone_connected && !$this->phoneConnected) {
                $this->phoneConnected = true;
                $this->verificationStep = 'connected';
                $this->dispatch('phone-connected');
            }

            // Check if verification completed
            if ($verification->status === 'verified') {
                $this->completeVerification();
                return;
            }

            // Update poll count
            $this->pollCount++;
            
            // Stop polling if max attempts reached
            if ($this->pollCount >= $this->maxPollAttempts) {
                $this->errorMessage = 'Session expired. Please generate a new QR code.';
                $this->qrCodeGenerated = false;
                $this->verificationStep = 'generate';
                $this->dispatch('stop-polling');
            }

        } catch (\Exception $e) {
            \Log::error('Connection check error: ' . $e->getMessage());
        }
    }

    public function regenerateQRCode()
    {
        $this->resetVerificationState();
        $this->generateQRCode();
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
        $this->dispatch('verification-completed');
    }

    public function redirectToDashboard()
    {
        return redirect()->route('dashboard');
    }

    public function backToMethodSelection()
    {
        return redirect()->route('verification.method');
    }

    private function resetVerificationState()
    {
        $this->verificationToken = '';
        $this->sessionCode = '';
        $this->qrCodeGenerated = false;
        $this->phoneConnected = false;
        $this->waitingForVerification = false;
        $this->errorMessage = '';
        $this->pollCount = 0;
        $this->verificationStep = 'generate';
    }

    public function getQRCodeUrl()
    {
        return route('mobile.verification', ['token' => $this->verificationToken]);
    }

    public function getQRCodeData()
    {
        return [
            'url' => $this->getQRCodeUrl(),
            'token' => $this->verificationToken,
            'session_code' => $this->sessionCode,
            'user_name' => Auth::user()->full_name,
            'expires_at' => now()->addMinutes(10)->toISOString()
        ];
    }

    // Calculate time remaining
    public function getTimeRemaining()
    {
        if (!$this->qrCodeGenerated) {
            return 0;
        }
        
        $verification = NidaVerification::where('user_id', Auth::id())
            ->where('verification_token', $this->verificationToken)
            ->first();

        if (!$verification || $verification->isTokenExpired()) {
            return 0;
        }

        return max(0, $verification->token_expires_at->diffInSeconds(now()));
    }

    // Format time remaining for display
    public function getFormattedTimeRemaining()
    {
        $seconds = $this->getTimeRemaining();
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        
        return sprintf('%d:%02d', $minutes, $remainingSeconds);
    }
}

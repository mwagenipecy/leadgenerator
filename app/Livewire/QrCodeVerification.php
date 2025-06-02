<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NidaVerification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Carbon\Carbon;

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
    public $timeRemaining = 360; // 6 minutes in seconds
    public $pollCount = 0;
    public $maxPollAttempts = 72; // 6 minutes at 5-second intervals
    public $qrCodeSvg = ''; // Store the actual QR code SVG
    public $expiresAt; // Store expiration timestamp
    public $isPolling = false;
    
    // Add reactive properties for real-time updates
    protected $listeners = [
        'refreshTimer' => 'updateTimer',
        'checkVerificationStatus' => 'checkConnection',
        'syncTimer' => 'syncTimerWithServer'
    ];

    public function mount()
    {
        // Check if there's an existing valid verification first
        $this->checkExistingVerification();
        
        if (!$this->qrCodeGenerated) {
            $this->generateQRCode();
        }
    }

    public function render()
    {
        return view('livewire.qr-code-verification');
    }

    /**
     * Check for existing verification session on page load/refresh
     */
    public function checkExistingVerification()
    {
        $verification = NidaVerification::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('verification_method', 'qr_code')
            ->where('token_expires_at', '>', now())
            ->first();

        if ($verification) {
            // Resume existing verification
            $this->verificationToken = $verification->verification_token;
            $this->sessionCode = strtoupper(substr($verification->verification_token, 0, 6));
            $this->qrCodeGenerated = true;
            $this->phoneConnected = $verification->phone_connected;
            $this->verificationStep = $verification->phone_connected ? 'connected' : 'waiting';
            $this->expiresAt = $verification->token_expires_at;
            $this->timeRemaining = max(0, $verification->token_expires_at->diffInSeconds(now()));
            
            // Regenerate QR code SVG for existing token
            $this->generateQRCodeSvg();
            
            // Start polling if not connected and not expired
            if (!$this->phoneConnected && $this->timeRemaining > 0) {
                $this->startPolling();
            }
        }
    }

    public function generateQRCode()
    {
        try {
            // Clean up any existing verification first
            $this->resetVerificationState();
            
            // Generate unique verification token and session code
            $this->verificationToken = Str::random(32);
            $this->sessionCode = strtoupper(Str::random(6));
            $expirationTime = now()->addMinutes(6);
            
            // Create or update verification record
            $verification = NidaVerification::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'nida_number' => Auth::user()->nida_number,
                    'verification_token' => $this->verificationToken,
                    'token_expires_at' => $expirationTime,
                    'verification_method' => 'qr_code',
                    'status' => 'pending',
                    'phone_connected' => false,
                    'expires_at' => now()->addHours(24)
                ]
            );

            // Store expiration time
            $this->expiresAt = $expirationTime;
            
            // Generate actual QR code using Bacon QR Code
            $this->generateQRCodeSvg();

            $this->qrCodeGenerated = true;
            $this->verificationStep = 'waiting';
            $this->errorMessage = '';
            $this->timeRemaining = 360; // Reset to 6 minutes
            $this->pollCount = 0;
            $this->phoneConnected = false;
            
            // Start polling for connection
            $this->startPolling();
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to generate QR code. Please try again.';
            \Log::error('QR code generation error: ' . $e->getMessage());
        }
    }

    /**
     * Generate QR code SVG
     */
    private function generateQRCodeSvg()
    {
        try {
            $renderer = new ImageRenderer(
                new RendererStyle(256, 2),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $this->qrCodeSvg = $writer->writeString($this->getQRCodeUrl());
        } catch (\Exception $e) {
            \Log::error('QR code SVG generation error: ' . $e->getMessage());
            $this->errorMessage = 'Failed to generate QR code. Please try again.';
        }
    }

    /**
     * Start polling process
     */
    private function startPolling()
    {
        if (!$this->isPolling) {
            $this->isPolling = true;
            $this->dispatch('start-polling');
        }
    }

    /**
     * Stop polling process
     */
    private function stopPolling()
    {
        if ($this->isPolling) {
            $this->isPolling = false;
            $this->dispatch('stop-polling');
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
                $this->stopPolling();
                return;
            }

            // Check if token expired
            if ($verification->isTokenExpired()) {
                $this->handleTimeout();
                return;
            }

            // Update time remaining from database
            $this->timeRemaining = max(0, $verification->token_expires_at->diffInSeconds(now()));
            $this->expiresAt = $verification->token_expires_at;

            // Check if phone connected
            if ($verification->phone_connected && !$this->phoneConnected) {
                $this->phoneConnected = true;
                $this->verificationStep = 'connected';
                $this->dispatch('phone-connected');
                // Continue polling until verification is complete
            }

            // Check if verification completed
            if ($verification->status === 'verified') {
                $this->completeVerification();
                return;
            }

            // Update poll count
            $this->pollCount++;
            
            // Stop polling if max attempts reached or time expired
            if ($this->pollCount >= $this->maxPollAttempts || $this->timeRemaining <= 0) {
                $this->handleTimeout();
            }

        } catch (\Exception $e) {
            \Log::error('Connection check error: ' . $e->getMessage());
        }
    }

    /**
     * Sync timer with server - called from frontend
     */
    public function syncTimerWithServer()
    {
        if (!$this->qrCodeGenerated || !$this->verificationToken) {
            return [
                'timeRemaining' => 0,
                'expired' => true
            ];
        }

        $verification = NidaVerification::where('user_id', Auth::id())
            ->where('verification_token', $this->verificationToken)
            ->first();

        if (!$verification || $verification->isTokenExpired()) {
            $this->handleTimeout();
            return [
                'timeRemaining' => 0,
                'expired' => true
            ];
        }

        $this->timeRemaining = max(0, $verification->token_expires_at->diffInSeconds(now()));
        $this->expiresAt = $verification->token_expires_at;
        
        return [
            'timeRemaining' => $this->timeRemaining,
            'expired' => $this->timeRemaining <= 0,
            'expiresAt' => $verification->token_expires_at->timestamp * 1000 // JavaScript timestamp
        ];
    }

    /**
     * Update timer from frontend
     */
    public function updateTimer()
    {
        return $this->syncTimerWithServer();
    }

    /**
     * Handle timeout and redirect to verification options
     */
    private function handleTimeout()
    {
        $this->errorMessage = 'QR code expired. Redirecting to verification options...';
        $this->qrCodeGenerated = false;
        $this->verificationStep = 'generate';
        $this->timeRemaining = 0;
        
        // Clean up verification record
        if ($this->verificationToken) {
            NidaVerification::where('user_id', Auth::id())
                ->where('verification_token', $this->verificationToken)
                ->delete();
        }
        
        $this->stopPolling();
        
        // Trigger redirect after showing message
        $this->dispatch('redirect-after-timeout');
    }

    public function regenerateQRCode()
    {
        $this->stopPolling();
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
        $this->stopPolling();
        $this->dispatch('verification-completed');
    }

    public function redirectToDashboard()
    {
        return redirect()->route('dashboard');
    }

    public function backToMethodSelection()
    {
        $this->stopPolling();
        $this->resetVerificationState();
        return redirect()->route('verification.options');
    }

    /**
     * Redirect to verification options (called from JS after timeout)
     */
    public function redirectToOptions()
    {
        $this->stopPolling();
        $this->resetVerificationState();
        return redirect()->route('verification.options');
    }

    private function resetVerificationState()
    {
        // Clean up database record
        if ($this->verificationToken) {
            NidaVerification::where('user_id', Auth::id())
                ->where('verification_token', $this->verificationToken)
                ->delete();
        }

        $this->verificationToken = '';
        $this->sessionCode = '';
        $this->qrCodeGenerated = false;
        $this->phoneConnected = false;
        $this->waitingForVerification = false;
        $this->errorMessage = '';
        $this->pollCount = 0;
        $this->verificationStep = 'generate';
        $this->qrCodeSvg = '';
        $this->timeRemaining = 360;
        $this->expiresAt = null;
        $this->isPolling = false;
    }

    public function getQRCodeUrl()
    {
        return route('verification.phone-photo.link', ['token' => $this->verificationToken]);
    }

    public function getQRCodeData()
    {
        return [
            'url' => $this->getQRCodeUrl(),
            'token' => $this->verificationToken,
            'session_code' => $this->sessionCode,
            'user_name' => Auth::user()->full_name,
            'expires_at' => $this->expiresAt ? $this->expiresAt->toISOString() : now()->addMinutes(6)->toISOString()
        ];
    }

    // Calculate time remaining
    public function getTimeRemaining()
    {
        if (!$this->qrCodeGenerated || !$this->expiresAt) {
            return 0;
        }
        
        return max(0, $this->expiresAt->diffInSeconds(now()));
    }

    // Format time remaining for display
    public function getFormattedTimeRemaining()
    {
        $seconds = $this->getTimeRemaining();
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        
        return sprintf('%d:%02d', $minutes, $remainingSeconds);
    }

    // Get expiration timestamp for JavaScript
    public function getExpirationTimestamp()
    {
        return $this->expiresAt ? $this->expiresAt->timestamp * 1000 : 0;
    }
}
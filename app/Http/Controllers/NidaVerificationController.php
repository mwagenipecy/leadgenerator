<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NidaVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NidaVerificationController extends Controller
{
    /**
     * Get QR verification data for mobile scanning
     */
    public function getQRVerificationData($token)
    {
        $verification = NidaVerification::where('verification_token', $token)
            ->where('token_expires_at', '>', now())
            ->with('user')
            ->first();

        if (!$verification) {
            return response()->json(['error' => 'Invalid or expired token'], 404);
        }

        return response()->json([
            'token' => $token,
            'user_name' => $verification->user->full_name,
            'nida_number' => $verification->user->nida_number,
            'expires_at' => $verification->token_expires_at->toISOString(),
            'verification_url' => route('mobile.verification', ['token' => $token])
        ]);
    }

    /**
     * Show mobile verification page for QR code scans
     */
    public function showMobileVerification($token)
    {
        $verification = NidaVerification::where('verification_token', $token)
            ->where('token_expires_at', '>', now())
            ->with('user')
            ->first();

        if (!$verification) {
            abort(404, 'Invalid or expired verification token');
        }

        // Mark phone as connected when the page loads
        $verification->update(['phone_connected' => true]);

        return view('verification.mobile', compact('verification', 'token'));
    }

    /**
     * Process mobile verification (photo upload from phone)
     */
    public function processMobileVerification(Request $request, $token)
    {
        $verification = NidaVerification::where('verification_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json(['error' => 'Invalid or expired token'], 404);
        }

        $request->validate([
            'photo' => 'required|image|max:5120',
            'photo_type' => 'required|in:id_document,fingerprint'
        ]);

        try {
            // Store the uploaded photo
            $photoPath = $request->file('photo')->store('verification-photos', 'private');
            
            // Process with NIDA verification
            $verificationResult = $this->processPhotoVerification($photoPath, $request->photo_type);
            
            if ($verificationResult['success']) {
                // Update verification record
                $verification->update([
                    'status' => 'verified',
                    'verified_at' => now(),
                    'verification_method' => 'mobile_photo',
                    'photo_type' => $request->photo_type,
                    'photo_path' => $photoPath,
                    'nida_response' => $verificationResult
                ]);

                // Update user record
                $verification->user->update([
                    'nida_verified_at' => now(),
                    'verification_status' => 'verified'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Verification completed successfully',
                    'redirect_url' => route('dashboard')
                ]);
            } else {
                // Clean up failed photo
                Storage::disk('private')->delete($photoPath);
                
                return response()->json([
                    'success' => false,
                    'message' => $verificationResult['message']
                ], 400);
            }

        } catch (\Exception $e) {
            \Log::error('Mobile verification error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Verification failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Mark phone as connected for QR code verification
     */
    public function markPhoneConnected($token)
    {
        $verification = NidaVerification::where('verification_token', $token)
            ->where('token_expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json(['error' => 'Invalid or expired token'], 404);
        }

        $verification->update(['phone_connected' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get verification status for polling
     */
    public function getVerificationStatus()
    {
        $user = Auth::user();
        $verification = $user->nidaVerification;

        return response()->json([
            'is_verified' => $user->isNidaVerified(),
            'status' => $verification->status ?? 'pending',
            'phone_connected' => $verification->phone_connected ?? false,
            'verified_at' => $verification->verified_at ?? null,
        ]);
    }

    /**
     * Process photo verification with NIDA (simulation)
     */
    private function processPhotoVerification($photoPath, $photoType)
    {
        try {
            sleep(1); // Simulate processing time
            
            // Basic image validation
            $photoContent = Storage::disk('private')->get($photoPath);
            $imageInfo = getimagesizefromstring($photoContent);
            
            if (!$imageInfo) {
                return [
                    'success' => false,
                    'message' => 'Invalid image format'
                ];
            }
            
            // Simulate verification result (85% success rate)
            $success = rand(1, 100) <= 85;
            
            if ($success) {
                return [
                    'success' => true,
                    'message' => 'Photo verified successfully',
                    'confidence_score' => rand(85, 99),
                    'verification_details' => [
                        'photo_type' => $photoType,
                        'quality_score' => rand(80, 100),
                        'verified_at' => now()->toISOString()
                    ]
                ];
            } else {
                $errorMessages = [
                    'Photo quality insufficient. Please ensure good lighting.',
                    'Unable to verify identity from the provided photo.',
                    'Image too blurry. Please capture in better lighting.',
                    'Photo does not match NIDA records clearly.'
                ];
                
                return [
                    'success' => false,
                    'message' => $errorMessages[array_rand($errorMessages)]
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Verification service error'
            ];
        }
    }
}
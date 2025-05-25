<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NidaVerification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'nida_number',
        'status',
        'verification_token',
        'token_expires_at',
        'phone_connected',
        'verification_method',
        'photo_type',
        'photo_path',
        'questionnaire_answers',
        'verified_at',
        'nida_response',
        'match_score',
        'confidence_score',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'expires_at' => 'datetime',
        'questionnaire_answers' => 'array',
        'nida_response' => 'array',
        'phone_connected' => 'boolean',
        'match_score' => 'integer',
        'confidence_score' => 'decimal:2',
    ];

    /**
     * Get the user that owns the verification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if verification is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Check if token is expired.
     */
    public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at < now();
    }

    /**
     * Get the verification method label.
     */
    public function getMethodLabelAttribute(): string
    {
        return match($this->verification_method) {
            'phone_photo' => 'Phone Camera',
            'qr_code' => 'QR Code',
            'mobile_photo' => 'Mobile Photo',
            'questionnaire' => 'Security Questions',
            default => 'Unknown',
        };
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'verified' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>',
            'pending' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>',
            'failed' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>',
            'expired' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Expired</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    /**
     * Scope for active verifications.
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope for verified records.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope for pending verifications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed verifications.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
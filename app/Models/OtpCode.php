<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpCode extends Model
{
    use HasFactory;

    protected $table = 'otp_codes';

    protected $fillable = [
        'user_id',
        'otp_hash',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expires_at);
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }

    public static function generateOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function scopeValid($query)
    {
        return $query->where('is_used', false)
            ->where('expires_at', '>', Carbon::now());
    }
}

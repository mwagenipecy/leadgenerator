<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\LenderAccountCreated;
use App\Mail\LenderApplicationStatusChanged;
class Lender extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'license_number',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'region',
        'postal_code',
        'website',
        'description',
        'status',
        'documents',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'user_id'
    ];

    protected $casts = [
        'documents' => 'array',
        'approved_at' => 'datetime',
    ];

    protected $dates = [
        'approved_at',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

 

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    public function scopeSuspended(Builder $query): Builder
    {
        return $query->where('status', 'suspended');
    }

    public function scopeByRegion(Builder $query, string $region): Builder
    {
        return $query->where('region', $region);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    // Helper Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'suspended' => 'gray',
            default => 'gray'
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending' => 'clock',
            'approved' => 'check-circle',
            'rejected' => 'x-circle',
            'suspended' => 'pause-circle',
            default => 'question-mark-circle'
        };
    }

 

    // Get full address as string
    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address,
            $this->city,
            $this->region,
            $this->postal_code
        ]));
    }

    // Check if lender has uploaded all required documents
    public function hasRequiredDocuments(): bool
    {
        $documents = $this->documents ?? [];
        $requiredTypes = ['business_license', 'tax_certificate', 'bank_statement'];
        
        foreach ($requiredTypes as $type) {
            if (!isset($documents[$type]) || empty($documents[$type])) {
                return false;
            }
        }
        
        return true;
    }

    // Get document URL by type
    public function getDocumentUrl(string $type): ?string
    {
        $documents = $this->documents ?? [];
        return $documents[$type] ?? null;
    }





    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

   



    // Create default user account for approved lender
    public function createUserAccount(int $approvedById): User
    {
        $password = $this->generateSecurePassword();
        
        $user = User::create([
            'name' => $this->contact_person,
            'email' => $this->email,
            'password' => Hash::make($password),
            'role' => 'lender',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $this->update([
            'user_id' => $user->id,
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedById
        ]);

        // Send account creation email
        try {
            Mail::to($user->email)->send(new LenderAccountCreated($user, $password));
        } catch (\Exception $e) {
            \Log::error('Failed to send lender account creation email: ' . $e->getMessage());
        }

        return $user;
    }

    // Reject lender application
    public function reject(string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        // Send rejection email
        try {
            Mail::to($this->email)->send(new LenderApplicationStatusChanged($this, 'rejected'));
        } catch (\Exception $e) {
            \Log::error('Failed to send lender rejection email: ' . $e->getMessage());
        }
    }

    // Suspend lender
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);

        // Deactivate user account
        if ($this->user) {
            $this->user->update(['is_active' => false]);
        }

        // Send suspension email
        try {
            Mail::to($this->email)->send(new LenderApplicationStatusChanged($this, 'suspended'));
        } catch (\Exception $e) {
            \Log::error('Failed to send lender suspension email: ' . $e->getMessage());
        }
    }

    // Reactivate lender
    public function reactivate(): void
    {
        $this->update(['status' => 'approved']);

        // Reactivate user account
        if ($this->user) {
            $this->user->update(['is_active' => true]);
        }
    }

    // Generate secure password
    private function generateSecurePassword(): string
    {
        return Str::random(2) . rand(1000, 9999) . Str::random(2) . '!';
    }




}
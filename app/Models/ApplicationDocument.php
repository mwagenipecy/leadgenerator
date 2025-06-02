<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ApplicationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id', 'document_type', 'document_name', 'file_path', 
        'file_type', 'file_size', 'mime_type', 'status', 'rejection_reason', 
        'verified_at', 'verified_by', 'document_date', 'expiry_date', 
        'extracted_data', 'is_required', 'file_hash', 'is_encrypted'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'document_date' => 'date',
        'expiry_date' => 'date',
        'extracted_data' => 'array',
        'is_required' => 'boolean',
        'is_encrypted' => 'boolean',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'uploaded');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    // Accessors
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getIsImageAttribute(): bool
    {
        return in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->file_type === 'pdf';
    }

    public function getStatusBadgeAttribute(): array
    {
        $badges = [
            'uploaded' => ['color' => 'blue', 'text' => 'Uploaded'],
            'verified' => ['color' => 'green', 'text' => 'Verified'],
            'rejected' => ['color' => 'red', 'text' => 'Rejected'],
            'expired' => ['color' => 'yellow', 'text' => 'Expired'],
        ];

        return $badges[$this->status] ?? ['color' => 'gray', 'text' => 'Unknown'];
    }

    // Methods
    public function verify(User $verifier): bool
    {
        if ($this->status !== 'uploaded') {
            return false;
        }

        $this->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $verifier->id,
            'rejection_reason' => null,
        ]);

        return true;
    }

    public function reject(User $verifier, string $reason): bool
    {
        if ($this->status !== 'uploaded') {
            return false;
        }

        $this->update([
            'status' => 'rejected',
            'verified_by' => $verifier->id,
            'rejection_reason' => $reason,
        ]);

        return true;
    }

    public function markAsExpired(): bool
    {
        if ($this->expiry_date && $this->expiry_date->isPast()) {
            $this->update(['status' => 'expired']);
            return true;
        }

        return false;
    }

    public function delete()
    {
        // Delete the actual file from storage
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }

        return parent::delete();
    }

    // Get document type display name
    public function getTypeDisplayNameAttribute(): string
    {
        $types = [
            'national_id' => 'National ID',
            'passport' => 'Passport',
            'drivers_license' => 'Driver\'s License',
            'birth_certificate' => 'Birth Certificate',
            'salary_slip' => 'Salary Slip',
            'employment_letter' => 'Employment Letter',
            'bank_statement' => 'Bank Statement',
            'utility_bill' => 'Utility Bill',
            'tax_certificate' => 'Tax Certificate',
            'business_license' => 'Business License',
            'financial_statement' => 'Financial Statement',
            'property_deed' => 'Property Deed',
            'vehicle_logbook' => 'Vehicle Logbook',
            'collateral_valuation' => 'Collateral Valuation',
            'guarantor_id' => 'Guarantor ID',
            'guarantor_salary_slip' => 'Guarantor Salary Slip',
            'other' => 'Other Document',
        ];

        return $types[$this->document_type] ?? ucwords(str_replace('_', ' ', $this->document_type));
    }

    // Static method to get required documents for application
    public static function getRequiredDocumentsForApplication(Application $application): array
    {
        $baseDocuments = [
            'national_id' => ['name' => 'National ID', 'required' => true],
            'salary_slip' => ['name' => 'Salary Slip', 'required' => false],
            'bank_statement' => ['name' => 'Bank Statement', 'required' => true],
        ];

        // Add employment-specific documents
        if ($application->employment_status === 'employed') {
            $baseDocuments['employment_letter'] = ['name' => 'Employment Letter', 'required' => true];
            $baseDocuments['salary_slip']['required'] = true;
        } elseif ($application->employment_status === 'self_employed') {
            $baseDocuments['business_license'] = ['name' => 'Business License', 'required' => true];
            $baseDocuments['tax_certificate'] = ['name' => 'Tax Certificate', 'required' => false];
            $baseDocuments['financial_statement'] = ['name' => 'Financial Statement', 'required' => true];
        }

        // Add address verification
        $baseDocuments['utility_bill'] = ['name' => 'Utility Bill', 'required' => true];

        // Add loan-specific documents if product is selected
        if ($application->loanProduct) {
            $productRequiredDocs = $application->loanProduct->required_documents ?? [];
            
            foreach ($productRequiredDocs as $docType) {
                if (!isset($baseDocuments[$docType])) {
                    $baseDocuments[$docType] = [
                        'name' => ucwords(str_replace('_', ' ', $docType)),
                        'required' => true
                    ];
                }
            }
        }

        return $baseDocuments;
    }
}
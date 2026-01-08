<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Permission;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasUuids;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'nida_number',
        'password',
        'email_verified_at',
        'nida_verified_at',
        'verification_status',
        'date_of_birth',
        'role',
        'is_active',
        'lender_id',
        'registration_type',
        'company_name',
        'company_tin',
        'company_contact_nida',
        'country',
        'passport_number',
        'company_verification_status',
        'company_verified_at',
        'company_verified_by',
        'company_verification_notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'nida_verified_at' => 'datetime',
            'company_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\QueuedResetPassword($token));
    }



    public function otps()
    {
        return $this->hasMany(UserOtp::class);
    }

    /**
     * Get the latest valid OTP for the user.
     */
    public function latestValidOtp()
    {
        return $this->otps()
                    ->valid()
                    ->latest()
                    ->first();
    }

    /**
     * Check if user has a valid OTP
     */
    public function hasValidOtp(): bool
    {
        return $this->otps()->valid()->exists();
    }




    public function nidaVerification()
    {
        return $this->hasOne(NidaVerification::class);
    }

    /**
     * Check if user is NIDA verified.
     */
    public function isNidaVerified(): bool
    {
        return $this->nida_verified_at !== null;
    }

    /**
     * Get the verification status badge.
     */
    public function getVerificationStatusBadgeAttribute(): string
    {
        return match($this->verification_status) {
            'verified' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>',
            'pending' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>',
            'failed' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>',
        };
    }


    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }



    public function lender()
    {
        return $this->belongsTo(Lender::class,'lender_id','id');
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isLender(): bool
    {
        return $this->role === 'lender';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    

    public function roles()
{
    return $this->belongsToMany(Role::class, 'user_roles')
                ->withPivot(['assigned_at', 'assigned_by', 'expires_at', 'metadata'])
                ->withTimestamps();
}

/**
 * The permissions that belong to the user directly.
 */
public function permissions()
{
    return $this->belongsToMany(Permission::class, 'user_permissions')
                ->withPivot(['type', 'assigned_at', 'assigned_by', 'expires_at', 'metadata'])
                ->withTimestamps();
}

/**
 * Check if user has a specific role
 */
public function hasRole(string $role): bool
{
    return $this->roles()->where('name', $role)->exists();
}

/**
 * Check if user has any of the given roles
 */
public function hasAnyRole(array $roles): bool
{
    return $this->roles()->whereIn('name', $roles)->exists();
}

/**
 * Check if user has a specific permission
 */
public function hasPermission(string $permission): bool
{
    // Check direct permissions first
    $directPermission = $this->permissions()
        ->where('name', $permission)
        ->wherePivot('type', 'grant')
        ->where(function ($query) {
            $query->whereNull('user_permissions.expires_at')
                  ->orWhere('user_permissions.expires_at', '>', now());
        })
        ->exists();

    if ($directPermission) {
        return true;
    }

    // Check if explicitly denied
    $deniedPermission = $this->permissions()
        ->where('name', $permission)
        ->wherePivot('type', 'deny')
        ->where(function ($query) {
            $query->whereNull('user_permissions.expires_at')
                  ->orWhere('user_permissions.expires_at', '>', now());
        })
        ->exists();

    if ($deniedPermission) {
        return false;
    }

    // Check role permissions
    return $this->roles()
        ->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })
        ->where(function ($query) {
            $query->whereNull('user_roles.expires_at')
                  ->orWhere('user_roles.expires_at', '>', now());
        })
        ->exists();
}

/**
 * Get all user permissions (from roles and direct assignments)
 */
public function getAllPermissions()
{
    $rolePermissions = $this->roles()
        ->with('permissions')
        ->where(function ($query) {
            $query->whereNull('user_roles.expires_at')
                  ->orWhere('user_roles.expires_at', '>', now());
        })
        ->get()
        ->pluck('permissions')
        ->flatten();

    $directPermissions = $this->permissions()
        ->wherePivot('type', 'grant')
        ->where(function ($query) {
            $query->whereNull('user_permissions.expires_at')
                  ->orWhere('user_permissions.expires_at', '>', now());
        })
        ->get();

    $deniedPermissions = $this->permissions()
        ->wherePivot('type', 'deny')
        ->where(function ($query) {
            $query->whereNull('user_permissions.expires_at')
                  ->orWhere('user_permissions.expires_at', '>', now());
        })
        ->pluck('name');

    return $rolePermissions
        ->merge($directPermissions)
        ->unique('id')
        ->reject(function ($permission) use ($deniedPermissions) {
            return $deniedPermissions->contains($permission->name);
        });
}

/**
 * Assign role to user
 */
public function assignRole(Role|string $role, ?User $assignedBy = null, ?\DateTime $expiresAt = null): void
{
    if (is_string($role)) {
        $role = Role::where('name', $role)->firstOrFail();
    }

    $this->roles()->syncWithoutDetaching([
        $role->id => [
            'assigned_at' => now(),
            'assigned_by' => $assignedBy?->id,
            'expires_at' => $expiresAt
        ]
    ]);

    $this->updateRoleLevel();
    $this->clearPermissionsCache();
}

/**
 * Remove role from user
 */
public function removeRole(Role|string $role): void
{
    if (is_string($role)) {
        $role = Role::where('name', $role)->firstOrFail();
    }

    $this->roles()->detach($role->id);
    $this->updateRoleLevel();
    $this->clearPermissionsCache();
}

/**
 * Give permission directly to user
 */
public function givePermission(Permission|string $permission, ?User $assignedBy = null, ?\DateTime $expiresAt = null): void
{
    if (is_string($permission)) {
        $permission = Permission::where('name', $permission)->firstOrFail();
    }

    $this->permissions()->syncWithoutDetaching([
        $permission->id => [
            'type' => 'grant',
            'assigned_at' => now(),
            'assigned_by' => $assignedBy?->id,
            'expires_at' => $expiresAt
        ]
    ]);

    $this->clearPermissionsCache();
}

/**
 * Deny permission to user
 */
public function denyPermission(Permission|string $permission, ?User $assignedBy = null, ?\DateTime $expiresAt = null): void
{
    if (is_string($permission)) {
        $permission = Permission::where('name', $permission)->firstOrFail();
    }

    $this->permissions()->syncWithoutDetaching([
        $permission->id => [
            'type' => 'deny',
            'assigned_at' => now(),
            'assigned_by' => $assignedBy?->id,
            'expires_at' => $expiresAt
        ]
    ]);

    $this->clearPermissionsCache();
}

/**
 * Update user's role level based on highest role
 */
public function updateRoleLevel(): void
{
    $highestLevel = $this->roles()->max('level') ?? 1;
    $this->update(['role_level' => $highestLevel]);
}

/**
 * Clear permissions cache
 */
public function clearPermissionsCache(): void
{
    $this->update([
        'permissions_cache' => null,
        'permissions_updated_at' => null
    ]);
}



public function profile()
{
    return $this->hasOne(UserProfile::class);
}

/**
 * Get company verification documents.
 */
public function companyVerificationDocuments()
{
    return $this->hasMany(CompanyVerificationDocument::class);
}

/**
 * Check if user is company verified.
 */
public function isCompanyVerified(): bool
{
    return $this->company_verification_status === 'verified';
}

/**
 * Check if user is from Tanzania.
 */
public function isFromTanzania(): bool
{
    return strtolower($this->country ?? '') === 'tanzania';
}

/**
 * Get the user who verified the company.
 */
public function companyVerifier()
{
    return $this->belongsTo(User::class, 'company_verified_by');
}

/**
 * Check if company verification is pending.
 */
public function isCompanyVerificationPending(): bool
{
    return $this->company_verification_status === 'pending';
}

/**
 * Check if company verification is rejected.
 */
public function isCompanyVerificationRejected(): bool
{
    return $this->company_verification_status === 'rejected';
}

}

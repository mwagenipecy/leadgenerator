<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * The roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withTimestamps();
    }

    /**
     * The users that have this permission directly.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions')
                    ->withPivot(['type', 'assigned_at', 'assigned_by', 'expires_at', 'metadata'])
                    ->withTimestamps();
    }

    /**
     * Get permissions grouped by category
     */
    public static function getGroupedPermissions(): array
    {
        return static::where('is_active', true)
                    ->orderBy('category')
                    ->orderBy('display_name')
                    ->get()
                    ->groupBy('category')
                    ->toArray();
    }
}
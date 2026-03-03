<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class SidebarMenuItem extends Model
{
    protected $fillable = [
        'key',
        'label_key',
        'route',
        'icon',
        'roles',
        'parent_id',
        'is_visible',
        'is_enabled',
        'sort_order',
    ];

    protected $casts = [
        'roles' => 'array',
        'is_visible' => 'boolean',
        'is_enabled' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SidebarMenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(SidebarMenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeForRole($query, ?string $role)
    {
        if (! $role) {
            return $query;
        }
        return $query->where(function ($q) use ($role) {
            $q->whereNull('roles')
                ->orWhereJsonContains('roles', $role);
        });
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get menu tree for the given user role (visible, enabled, role-filtered).
     */
    public static function getTreeForUser(?string $role): \Illuminate\Support\Collection
    {
        $cacheKey = 'sidebar_menu_' . ($role ?? 'guest');
        return Cache::remember($cacheKey, 300, function () use ($role) {
            return static::query()
                ->roots()
                ->visible()
                ->forRole($role)
                ->orderBy('sort_order')
                ->with(['children' => function ($q) use ($role) {
                    $q->visible()->forRole($role)->orderBy('sort_order');
                }])
                ->get();
        });
    }

    /**
     * Clear menu cache (call after any menu item update).
     */
    public static function clearMenuCache(): void
    {
        $roles = ['lender', 'borrower', 'super_admin', 'guest'];
        foreach ($roles as $role) {
            Cache::forget('sidebar_menu_' . $role);
        }
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearMenuCache());
        static::deleted(fn () => static::clearMenuCache());
    }
}

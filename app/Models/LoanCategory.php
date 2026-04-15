<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LoanCategory extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'name_en',
        'name_sw',
        'slug',
        'description',
        'description_en',
        'description_sw',
        'image_path',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $slugSource = $category->name_en ?? $category->name ?? $category->name_sw;
                $category->slug = Str::slug($slugSource);
            }
        });
    }

    /**
     * Get active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get categories ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_en')->orderBy('name');
    }

    public function getLocalizedNameAttribute(): string
    {
        if (app()->getLocale() === 'sw') {
            return $this->name_sw ?: $this->name_en ?: $this->name;
        }

        return $this->name_en ?: $this->name_sw ?: $this->name;
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        if (app()->getLocale() === 'sw') {
            return $this->description_sw ?: $this->description_en ?: $this->description;
        }

        return $this->description_en ?: $this->description_sw ?: $this->description;
    }

    /**
     * Get loan products for this category
     */
    public function loanProducts()
    {
        return $this->hasMany(\App\Models\LoanProduct::class, 'loan_category_id');
    }
}

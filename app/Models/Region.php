<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name',
        'code',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function loanProducts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\LoanProduct::class, 'loan_product_region')->withTimestamps();
    }
}

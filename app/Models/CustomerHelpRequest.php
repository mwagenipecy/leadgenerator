<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerHelpRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    public const STATUS_NEW = 'new';
    public const STATUS_ATTENDED = 'attended';

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }
}

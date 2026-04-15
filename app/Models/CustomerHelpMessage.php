<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerHelpMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_help_request_id',
        'user_id',
        'sender_type',
        'message',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(CustomerHelpRequest::class, 'customer_help_request_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

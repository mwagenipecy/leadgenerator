<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
        'type',
        'updated_by',
    ];

    protected $casts = [
        'updated_by' => 'integer',
    ];

    /**
     * Get the user who last updated this setting
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get a setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function setValue($key, $value, $description = null, $type = 'string')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description,
                'type' => $type,
                'updated_by' => auth()->id(),
            ]
        );
    }

    /**
     * Get multiple settings as key-value pairs
     */
    public static function getSettings(array $keys)
    {
        return static::whereIn('key', $keys)->pluck('value', 'key');
    }
}
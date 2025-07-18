<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'setting_key',
        'setting_value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a setting value for a user
     */
    public static function getSetting($userId, $key, $default = null)
    {
        $setting = self::where('user_id', $userId)
            ->where('setting_key', $key)
            ->first();
        
        return $setting ? json_decode($setting->setting_value, true) : $default;
    }

    /**
     * Set a setting value for a user
     */
    public static function setSetting($userId, $key, $value)
    {
        return self::updateOrCreate(
            ['user_id' => $userId, 'setting_key' => $key],
            ['setting_value' => json_encode($value)]
        );
    }
}
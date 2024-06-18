<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = 'config';
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get($key)
    {
        return self::where('key', $key)->first()->value;
    }

    public static function set($key, $value)
    {
        $config = self::where('key', $key)->first();
        if ($config) {
            $config->value = $value;
            $config->save();
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}

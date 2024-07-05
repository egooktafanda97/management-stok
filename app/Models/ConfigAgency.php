<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigAgency extends Model
{
    use HasFactory;

    protected $table = 'config_agency';
    protected $fillable = [
        'agency_id',
        'key',
        'value',
    ];

    public static function get($key, $agency_id)
    {
        return self::where('key', $key)->where('agency_id', $agency_id)->first()->value;
    }

    public static function set($key, $value, $agency_id)
    {
        $config = self::where('key', $key)->where('agency_id', $agency_id)->first();
        if ($config) {
            $config->value = $value;
            $config->save();
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'agency_id' => $agency_id,
            ]);
        }
    }
}

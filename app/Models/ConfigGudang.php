<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigGudang extends Model
{
    use HasFactory;

    protected $table = 'config_gudang';
    protected $fillable = [
        'agency_id',
        'gudang_id',
        'key',
        'value',
    ];

    public static function get($key, $agency_id, $gudang_id)
    {
        return self::where('key', $key)->where('agency_id', $agency_id)->where('gudang_id', $gudang_id)->first()->value;
    }

    public static function set($key, $value, $agency_id, $gudang_id)
    {
        $config = self::where('key', $key)->where('agency_id', $agency_id)->where('gudang_id', $gudang_id)->first();
        if ($config) {
            $config->value = $value;
            $config->save();
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'agency_id' => $agency_id,
                'gudang_id' => $gudang_id,
            ]);
        }
    }
}

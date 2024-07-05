<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSatuan extends Model
{
    use HasFactory;

    protected $table = 'jenis_satuans';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'name'
    ];

    /**
     * Get the agency that owns the jenis satuan.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang that owns the jenis satuan.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param int|null $id
     * @return array
     */
    public static function rules($id = null)
    {
        return [
            'agency_id' => 'required|exists:agency,id',
            'gudang_id' => 'required|exists:gudang,id',
            'name' => 'required|string|max:255',
        ];
    }
}

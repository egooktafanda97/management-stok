<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    use HasFactory;
    protected $table = 'rak';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'code',
        'barcode',
        'nama',
        'kapasitas'
    ];

    /**
     * Get the agency that owns the rak.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang that owns the rak.
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
            'code' => 'nullable|integer',
            'barcode' => 'nullable|integer',
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer',
        ];
    }
}

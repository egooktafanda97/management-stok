<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'name',
        'alamat_supplier',
        'nomor_telepon_supplier'
    ];

    /**
     * Get the agency that owns the supplier.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
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
            'nama_supplier' => 'required|string|max:255',
            'alamat_supplier' => 'nullable|string',
            'nomor_telepon_supplier' => 'nullable|string|max:255',
        ];
    }
}

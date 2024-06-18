<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;

    protected $table = 'harga';

    protected $fillable = [
        'user_created_id',
        'agency_id',
        'gudang_id',
        'produks_id',
        'harga',
        'harga_decimal',
        'jenis_satuan_id',
        'user_update_id'
    ];

    /**
     * Get the user that created the harga.
     */
    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_created_id');
    }

    /**
     * Get the agency that owns the harga.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang that owns the harga.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Get the produk associated with the harga.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }

    /**
     * Get the jenis satuan associated with the harga.
     */
    public function jenisSatuan()
    {
        return $this->belongsTo(JenisSatuan::class, 'jenis_satuan_id');
    }

    /**
     * Get the user that updated the harga.
     */
    public function userUpdate()
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param int|null $id
     * @return array
     */
    public static function rules($id = null): array
    {
        $rule = [
            'user_created_id' => 'required|exists:users,id',
            'agency_id' => 'required|exists:agency,id',
            'gudang_id' => 'required|exists:gudang,id',
            'produks_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
            'harga_decimal' => 'nullable|numeric',
            'jenis_satuan_id' => 'required|exists:jenis_satuans,id',
            'user_update_id' => 'nullable|exists:users,id',
        ];
        if ($id) {
            $rule = [
                'user_created_id' => 'nullable|exists:users,id',
                'agency_id' => 'nullable|exists:agency,id',
                'gudang_id' => 'nullable|exists:gudang,id',
                'produks_id' => 'nullable|exists:produks,id',
                'jumlah' => 'nullable|integer|min:1',
                'harga_decimal' => 'nullable|numeric',
                'jenis_satuan_id' => 'nullable|exists:jenis_satuans,id',
                'user_update_id' => 'nullable|exists:users,id',
            ];
        }
        return $rule;
    }
}

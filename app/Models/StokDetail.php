<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDetail extends Model
{
    use HasFactory;

    protected $table = 'stok_detail';

    protected $fillable = [
        'stok_id',
        'agency_id',
        'gudang_id',
        'produks_id',
        'jumlah',
        'satuan_id',
        'jumlah_sebelumnya',
        'satuan_sebelumnya_id',
        'tipe'
    ];

    /**
     * Get the stok that owns the stok detail.
     */
    public function stok()
    {
        return $this->belongsTo(Stok::class);
    }

    /**
     * Get the agency that owns the stok detail.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang that owns the stok detail.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Get the produk associated with the stok detail.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Get the jenis satuan associated with the stok detail.
     */
    public function jenisSatuan()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_id');
    }

    /**
     * Get the previous jenis satuan associated with the stok detail.
     */
    public function satuanSebelumnya()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_sebelumnya_id');
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
            'stok_id' => 'required|exists:stoks,id',
            'agency_id' => 'required|exists:agency,id',
            'gudang_id' => 'required|exists:gudang,id',
            'produks_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer',
            'satuan_id' => 'required|exists:jenis_satuans,id',
            'jumlah_sebelumnya' => 'required|integer',
            'satuan_sebelumnya_id' => 'required|exists:jenis_satuans,id',
            'tipe' => 'required|in:masuk,keluar',
        ];
    }
}

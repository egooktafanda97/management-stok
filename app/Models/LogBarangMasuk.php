<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBarangMasuk extends Model
{
    use HasFactory;
    protected $table = 'log_barang_masuk';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'user_create_id',
        'produks_id',
        'supplier_id',
        'harga_beli',
        'jumlah_barang_masuk',
        'satuan_beli_id',
        'jumlah_stok_sisa',
        'satuan_stok_sisa_id',
        'status_id'
    ];

    /**
     * Get the agency that owns the log barang masuk.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang that owns the log barang masuk.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Get the user that created the log barang masuk.
     */
    public function userCreate()
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    /**
     * Get the produk associated with the log barang masuk.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }

    /**
     * Get the supplier associated with the log barang masuk.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the satuan beli associated with the log barang masuk.
     */
    public function satuanBeli()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_beli_id');
    }

    /**
     * Get the satuan stok sisa associated with the log barang masuk.
     */
    public function satuanStokSisa()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_stok_sisa_id');
    }

    /**
     * Get the status associated with the log barang masuk.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
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
            'user_create_id' => 'required|exists:users,id',
            'produks_id' => 'required|exists:produks,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'harga_beli' => 'required|integer',
            'jumlah_barang_masuk' => 'required|integer|min:1',
            'satuan_beli_id' => 'required|exists:jenis_satuans,id',
            'jumlah_stok_sisa' => 'required|integer|min:0',
            'satuan_stok_sisa_id' => 'required|exists:jenis_satuans,id',
            'status_id' => 'required|exists:status,id',
        ];
    }
}

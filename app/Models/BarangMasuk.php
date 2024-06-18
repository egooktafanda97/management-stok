<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;
    protected $table = 'barang_masuk';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'user_created_id',
        'produks_id',
        'supplier_id',
        'harga_beli',
        'jumlah_barang_masuk',
        'satuan_beli_id',
        'jumlah_sebelumnya',
        'satuan_sebelumnya_id',
        'status_id'
    ];

    // Relasi dengan tabel 'agency'
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    // Relasi dengan tabel 'gudang'
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    // Relasi dengan tabel 'users'
    public function userCreate()
    {
        return $this->belongsTo(User::class, 'user_created_id');
    }

    // Relasi dengan tabel 'produks'
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }

    // Relasi dengan tabel 'suppliers'
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi dengan tabel 'jenis_satuans' untuk satuan_beli
    public function satuanBeli()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_beli_id');
    }



    // Relasi dengan tabel 'status'
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    // Aturan validasi
    public static function rules($id = null)
    {
        return [
            'agency_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:agency,id',
            'gudang_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:gudang,id',
            'user_created_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:users,id',
            'produks_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:produks,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'harga_beli' => 'required|integer',
            'jumlah_barang_masuk' => 'required|integer',
            'satuan_beli_id' => 'required|integer|exists:jenis_satuans,id',
            'jumlah_sebelumnya' => 'required|integer',
            'satuan_sebelumnya_id' => 'required|integer|exists:jenis_satuans,id',
            'status_id' => 'required|integer|exists:status,id',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stoks';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'produks_id',
        'jumlah',
        'satuan_id',
        'jumlah_sebelumnya',
        'satuan_sebelumnya_id',
        'keterangan'
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

    // Relasi dengan tabel 'produks'
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }

    // Relasi dengan tabel 'jenis_satuans' untuk satuan
    public function satuan()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_id');
    }

    // Relasi dengan tabel 'jenis_satuans' untuk satuan_sebelumnya
    public function satuanSebelumnya()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_sebelumnya_id');
    }

    // Aturan validasi
    public static function rules($id = null)
    {
        return [
            'agency_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:agency,id',
            'gudang_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:gudang,id',
            'produks_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:produks,id',
            'jumlah' => 'required|integer|min:0',
            'satuan_id' => 'required|integer|exists:jenis_satuans,id',
            'jumlah_sebelumnya' => 'required|integer|min:0',
            'satuan_sebelumnya_id' => 'required|integer|exists:jenis_satuans,id',
            'keterangan' => 'nullable|string',
        ];
    }
}

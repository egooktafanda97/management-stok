<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksConfig extends Model
{
    use HasFactory;

    protected $table = 'produks_config';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'produks_id',
        'satuan_stok_id',
    ];

    /**
     * Define relationships
     */

    // Agency relationship
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    // Gudang relationship
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    // Produks relationship
    public function produks()
    {
        return $this->belongsTo(Produk::class);
    }

    // Jenis Satuan relationship
    public function jenisSatuan()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_stok_id');
    }

    // rules
    public static function rules($id = [])
    {
        return [
            'agency_id' => 'required|exists:agency,id',
            'gudang_id' => 'required|exists:gudang,id',
            'produks_id' => 'required|exists:produks,id',
            'satuan_stok_id' => 'required|exists:jenis_satuans,id',
        ];
    }
}

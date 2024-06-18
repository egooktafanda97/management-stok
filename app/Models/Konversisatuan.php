<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konversisatuan extends Model
{
    use HasFactory;
    protected $table = 'konversisatuan';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'user_created_id',
        'produks_id',
        'satuan_id',
        'satuan_konversi_id',
        'nilai_konversi',
        'status_id',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_created_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }

    public function satuan()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_id');
    }

    public function satuanKonversi()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_konversi_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }


    public static function rules($id = null)
    {
        return [
            'agency_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:agency,id',
            'gudang_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:gudang,id',
            'user_created_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:users,id',
            'produks_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:produks,id',
            'satuan_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:jenis_satuans,id',
            'satuan_konversi_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:jenis_satuans,id',
            'nilai_konversi' => ($id ? 'nullable|' : 'required|') . 'numeric',
            'status_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:status,id',
        ];
    }
}

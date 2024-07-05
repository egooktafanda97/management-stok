<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPrieces extends Model
{
    use HasFactory;
    protected $table = 'unit_priece';

    protected $fillable = [
        'user_created_id',
        'agency_id',
        'gudang_id',
        'produks_id',
        'name',
        'priece',
        'priece_decimal',
        // 'jumlah_satan_jual',
        'jenis_satuan_jual_id',
        'diskon',
        'status_id',
        'user_update_id',
    ];

    /**
     * Define relationships
     */

    // User created relationship
    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_created_id');
    }

    // User updated relationship
    public function userUpdated()
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }

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

    // Jenis Satuan Jual relationship
    public function jenisSatuanJual()
    {
        return $this->belongsTo(JenisSatuan::class, 'jenis_satuan_jual_id');
    }

    // Status relationship
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public static function rules($id = null)
    {
        $roles = [
            'user_created_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:users,id',
            'agency_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:agency,id',
            'gudang_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:gudang,id',
            'produks_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:produks,id',
            'name' => ($id ? 'nullable|' : 'required|') . 'string',
            'priece' => ($id ? 'nullable|' : 'required|') . 'integer',
            'priece_decimal' => ($id ? 'nullable|' : 'required|') . 'numeric',
            // 'jumlah_satan_jual' => ($id ? 'nullable|' : 'required|') . 'integer',
            'jenis_satuan_jual_id' => ($id ? 'nullable|' : 'required|') . 'integer',
            'diskon' => ($id ? 'nullable|' : 'required|') . 'integer',
            'status_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:status,id',
            'user_update_id' => ($id ? 'nullable|' : 'required|') . 'integer|exists:users,id',
        ];

        return $roles;
    }
}

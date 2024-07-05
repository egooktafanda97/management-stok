<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'user_id',
        'name',
        'deskripsi',
        'gambar',
        'jenis_produk_id',
        'barcode',
        'rak_id',
        'status_id'
    ];

    /**
     * Get the agency that owns the produk.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang that owns the produk.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Get the user that created the produk.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the jenis produk associated with the produk.
     */
    public function jenisProduk()
    {
        return $this->belongsTo(JenisProduk::class);
    }

    /**
     * Get the rak that stores the produk.
     */
    public function rak()
    {
        return $this->belongsTo(Rak::class);
    }

    /**
     * Get the status associated with the produk.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    // satuan stok
    public function satuanStok()
    {
        return $this->hasOne(ProduksConfig::class, 'produks_id');
    }

    // unitPrieces
    public function unitPrieces()
    {
        return $this->hasMany(UnitPrieces::class, 'produks_id');
    }

    // stok
    public function stok()
    {
        return $this->belongsTo(Stok::class, 'id', 'produks_id');
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @param int|null $id
     * @return array
     */
    public static function rules($id = null)
    {
        $roles =  [
            'agency_id' => 'required|exists:agency,id',
            'gudang_id' => 'required|exists:gudang,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|string',
            'jenis_produk_id' => 'required|exists:jenis_produks,id',
            'barcode' => 'nullable|string|max:100',
            'rak_id' => 'nullable|exists:rak,id',
            'status_id' => 'required|exists:status,id',
        ];
        if ($id) {
            $roles['agency_id'] = 'nullable|exists:agency,id';
            $roles['gudang_id'] = 'nullable|exists:gudang,id';
            $roles['user_id'] = 'nullable|exists:users,id';
            $roles['name'] = 'nullable|string|max:255';
            $roles['deskripsi'] = 'nullable|string';
            $roles['gambar'] = 'nullable|string';
            $roles['jenis_produk_id'] = 'nullable|exists:jenis_produks,id';
            $roles['barcode'] = 'nullable|string|max:100';
            $roles['rak_id'] = 'nullable|exists:rak,id';
            $roles['status_id'] = 'nullable|exists:status,id';
        }
        return $roles;
    }


    public static function allWith()
    {
        return [
            'agency',
            'gudang',
            'user',
            'jenisProduk',
            'rak',
            'status',
            'satuanStok' => function ($q) {
                $q->with('jenisSatuan');
            },
            'unitPrieces' => function ($q) {
                $q->with('jenisSatuanJual');
            },
            'stok' => function ($q) {
                $q->with('satuan', 'satuanSebelumnya');
            }
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxDetail extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi plural
    protected $table = 'detail_transaksi';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'agency_id',
        'gudang_id',
        'user_kasir_id',
        'user_buyer_id',
        'invoice_id',
        'transaksi_id',
        'produks_id',
        'unit_priece_id',
        'satuan_id',
        'priece',
        'priece_decimal',
        'jumlah',
        'total',
        'diskon',
        'status_id'
    ];

    // Definisikan relasi ke model lain

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function userKasir()
    {
        return $this->belongsTo(User::class, 'user_kasir_id');
    }

    public function userBuyer()
    {
        return $this->belongsTo(User::class, 'user_buyer_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }

    public function unitPrice()
    {
        return $this->belongsTo(UnitPrieces::class, 'unit_priece_id');
    }

    public function satuan()
    {
        return $this->belongsTo(JenisSatuan::class, 'satuan_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    // allWith
    public static function allWith()
    {
        return [
            'agency',
            'gudang',
            'userKasir',
            'userBuyer',
            'invoice',
            'transaksi',
            'produk' => function ($query) {
                $query->with(Produk::allWith());
            },
            'unitPrice',
            'satuan',
            'status'
        ];
    }

    public static function rules($id = null)
    {
        return [
            'agency_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:agency,id|integer',
            'gudang_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:gudang,id|integer',
            'user_kasir_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'user_buyer_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'invoice_id' => (empty($id) ? 'required|' : 'nullable|') . 'string|max:100',
            'transaksi_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:transaksi,id|integer',
            'produks_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:produks,id|integer',
            'unit_priece_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:unit_priece,id|integer',
            'satuan_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:jenis_satuans,id|integer',
            'priece' => (empty($id) ? 'required|' : 'nullable|') . 'numeric',
            'priece_decimal' => (empty($id) ? 'required|' : 'nullable|') . 'numeric',
            'jumlah' => (empty($id) ? 'required|' : 'nullable|') . 'integer',
            'total' => (empty($id) ? 'required|' : 'nullable|') . 'integer',
            'diskon' => 'required|integer',
            'status_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:status,id|integer',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Histories extends Model
{
    use HasFactory;
    protected $fillable = [
        'agency_id',
        'gudang_id',
        'kasir_id',
        'user_kasir_id',
        'user_trx_id',
        'invoice_id',
        'tanggal',
        'jumlah',
        'saldo_awal',
        'saldo_akhir',
        'total',
        'status_id',
    ];



    public function invoice()
    {
        return $this->belongsTo(Invoices::class);
    }

    public function kasir()
    {
        return $this->belongsTo(Kasir::class);
    }

    public function userKasir()
    {
        return $this->belongsTo(User::class, 'user_kasir_id');
    }

    public function userTrx()
    {
        return $this->belongsTo(User::class, 'user_trx_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public static function rules($id = null)
    {
        $rules =  [
            'agency_id' => 'required|integer',
            'gudang_id' => 'required|integer',
            'kasir_id' => 'nullable|integer',
            'user_kasir_id' => 'nullable|integer',
            'user_trx_id' => 'nullable|integer',
            'invoice_id' => 'required|integer',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer',
            'saldo_awal' => 'required|integer',
            'saldo_akhir' => 'required|integer',
            'total' => 'required|integer',
            'status_id' => 'required|integer',
        ];
        return $rules;
    }
}

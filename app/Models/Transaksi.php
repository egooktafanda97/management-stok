<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'kasir_id',
        'user_kasir_id',
        'user_buyer_id',
        'invoice_id',
        'tanggal',
        'diskon',
        'total_diskon',
        'tax',
        'tax_deduction',
        'total_gross',
        'sub_total',
        'payment_type_id',
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

    public function kasir()
    {
        return $this->belongsTo(Kasir::class, 'kasir_id');
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

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    // transaksi detail
    public function transaksiDetail()
    {
        return $this->hasMany(TrxDetail::class, 'transaksi_id');
    }

    // buyer
    public function buyer()
    {
        return $this->belongsTo(GeneralActor::class, 'user_buyer_id', 'user_id');
    }

    // allWith
    public static function allWith()
    {
        return [
            'agency',
            'gudang',
            'kasir',
            'userKasir',
            'buyer' => function ($query) {
                $query->with('user');
            },
            'invoice',
            'paymentType',
            'transaksiDetail' => function ($query) {
                $query->with(TrxDetail::allWith());
            },
            'status'
        ];
    }

    public static function rules($id = null)
    {
        return [
            'agency_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:agency,id|integer',
            'gudang_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:gudang,id|integer',
            'kasir_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:kasir,id|integer',
            'user_kasir_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'user_buyer_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'invoice_id' => (empty($id) ? 'required|' : 'nullable|') . 'string|max:100',
            'tanggal' => (empty($id) ? 'required|' : 'nullable|') . 'date',
            'diskon' => 'nullable|integer',
            'total_diskon' => 'nullable|integer',
            'tax' => 'nullable|integer',
            'tax_deduction' => 'nullable|integer',
            'total_gross' => 'required|integer',
            'sub_total' => 'required|integer',
            'payment_type_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:payment_types,id|integer',
            'status_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:status,id|integer',
        ];
    }
}

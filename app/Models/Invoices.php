<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi plural
    protected $table = 'invoices';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'agency_id',
        'gudang_id',
        'users_merchant_id',
        'users_trx_id',
        'invoice_id',
        'trx_types_id',
        'payment_type_id',
        'dates',
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

    public function usersMerchant()
    {
        return $this->belongsTo(User::class, 'users_merchant_id');
    }

    public function usersTrx()
    {
        return $this->belongsTo(User::class, 'users_trx_id');
    }

    public function trxType()
    {
        return $this->belongsTo(TrxTypes::class, 'trx_types_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public static function rules($id = null)
    {
        return [
            'agency_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:agency,id|integer',
            'gudang_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:gudang,id|integer',
            'users_merchant_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'users_trx_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'invoice_id' => (empty($id) ? 'required|' : 'nullable|') . 'string|max:100',
            'trx_types_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:trx_types,id|integer',
            'payment_type_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:payment_types,id|integer',
            'dates' => (empty($id) ? 'required|' : 'nullable|') . 'date',
            'status_id' => (empty($id) ? 'required|' : 'nullable|') . 'integer',
        ];
    }
}

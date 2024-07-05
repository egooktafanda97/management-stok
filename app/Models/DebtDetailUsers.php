<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtDetailUsers extends Model
{
    use HasFactory;

    protected $table = 'debt_detail_users';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'user_kasir_id',
        'user_debt_id',
        'invoice_id',
        'total_hutang',
        'total_bayar',
        'total_sisa',
        'payment_type_id',
        'status_id'
    ];

    /**
     * Get the agency associated with the debt detail user.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang associated with the debt detail user.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Get the user that is the cashier for the debt detail.
     */
    public function userKasir()
    {
        return $this->belongsTo(User::class, 'user_kasir_id');
    }

    /**
     * Get the user that has the debt.
     */
    public function userDebt()
    {
        return $this->belongsTo(User::class, 'user_debt_id');
    }

    /**
     * Get the invoice associated with the debt detail user.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoices::class);
    }

    /**
     * Get the payment type associated with the debt detail user.
     */
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    /**
     * Get the status associated with the debt detail user.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public static function rules($id = null)
    {
        return [
            'agency_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:agency,id|integer',
            'gudang_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:gudang,id|integer',
            'user_kasir_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'user_debt_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'invoice_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:invoices,id|integer',
            'total_hutang' => 'required|integer',
            'total_bayar' => 'required|integer',
            'total_sisa' => 'required|integer',
            'payment_type_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:payment_types,id|integer',
            'status_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:status,id|integer',
        ];
    }
}

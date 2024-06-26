<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtUsers extends Model
{
    use HasFactory;
    protected $table = 'debt_users';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'user_debt_id',
        'total',
    ];

    /**
     * Get the agency associated with the debt user.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang associated with the debt user.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Get the user that has the debt.
     */
    public function userDebt()
    {
        return $this->belongsTo(User::class, 'user_debt_id');
    }

    // RELATION GENERAL ACTOR
    public function generalActor()
    {
        return $this->belongsTo(GeneralActor::class, 'user_debt_id', 'user_id');
    }

    public static function rules($id = null)
    {
        return [
            'agency_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:agency,id|integer',
            'gudang_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:gudang,id|integer',
            'user_debt_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'total' => 'required|integer',
        ];
    }
}

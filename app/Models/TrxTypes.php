<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxTypes extends Model
{
    use HasFactory;

    protected $table = 'trx_types';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'users_create_id',
        'name',
        'descriptions'
    ];

    /**
     * Get the agency that owns the transaction type.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the gudang that owns the transaction type.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Get the user that created the transaction type.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_create_id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param int|null $id
     * @return array
     */
    public static function rules($id = null)
    {
        return [
            'agency_id' => 'required|exists:agency,id',
            'gudang_id' => 'required|exists:gudang,id',
            'users_create_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'descriptions' => 'required|string|max:255',
        ];
    }
}

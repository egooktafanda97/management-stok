<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    use HasFactory;
    protected $table = 'kasir';

    protected $fillable = [
        'agency_id',
        'user_id',
        'gudang_id',
        'nama',
        'alamat',
        'telepon',
        'deskripsi',
        'saldo'
    ];

    /**
     * Get the agency that owns the kasir.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the user that owns the kasir.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the gudang associated with the kasir.
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
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
            'agency_id' => (!$id ? 'required|' : 'nullable|') . 'exists:agency,id',
            'user_id' => (!$id ? 'required|' : 'nullable|') . 'exists:users,id',
            'gudang_id' => (!$id ? 'required|' : 'nullable|') . 'exists:gudang,id',
            'nama' => (!$id ? 'required|' : 'nullable|') . 'string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string|max:255',
            'saldo' => 'nullable|integer|min:0',
        ];
    }
}

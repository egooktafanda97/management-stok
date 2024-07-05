<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;
    protected $table = 'gudang';

    protected $fillable = [
        'agency_id',
        'user_id',
        'nama',
        'alamat',
        'telepon',
        'logo',
        'deskripsi',
        'status_id'
    ];

    /**
     * Get the agency that owns the gudang.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the user that owns the gudang.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status associated with the gudang.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param int|null $id
     * @return array
     */
    public static function rules($id = null)
    {
        $role =  [
            'agency_id' => (!$id ? 'required|' : 'nullable|') . 'exists:agency,id',
            'user_id' => (!$id ? 'required|' : 'nullable|') . 'exists:users,id',
            'nama' => (!$id ? 'required|' : 'nullable|') . 'string|max:255',
            'alamat' => (!$id ? 'required|' : 'nullable|') . 'string|max:255',
            'telepon' => (!$id ? 'required|' : 'nullable|') . 'string|max:255',
            'logo' =>  'nullable|string|max:255',
            'deskripsi' => (!$id ? 'required|' : 'nullable|') . 'nullable|string|max:255',
            'status_id' => (!$id ? 'required|' : 'nullable|') . 'exists:status,id',
        ];

        return $role;
    }
}

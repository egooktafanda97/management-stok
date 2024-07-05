<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;
    protected $table = 'agency';

    protected $fillable = [
        'user_id',
        'oncard_instansi_id',
        'kode_instansi',
        'apikeys',
        'nama',
        'alamat',
        'status_id'
    ];

    /**
     * Get the user that owns the agency.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the status associated with the agency.
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
        return [
            'user_id' => (!$id ? 'required|' : 'nullable|') . 'exists:users,id',
            'oncard_instansi_id' => (!$id ? 'required|' : 'nullable|') . 'integer',
            'kode_instansi' => (!$id ? 'required|' : 'nullable|') . 'string|max:100|unique:agency,kode_instansi' . ($id ? ",$id" : ''),
            'apikeys' => 'nullable',
            'nama' => (!$id ? 'required|' : 'nullable|') . 'string|max:100',
            'alamat' => (!$id ? 'required|' : 'nullable|') . 'string',
            'status_id' => (!$id ? 'required|' : 'nullable|') . 'exists:status,id',
        ];
    }
}

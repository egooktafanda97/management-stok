<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralActor extends Model
{
    use HasFactory;

    protected $table = 'general_actor';

    protected $fillable = [
        'agency_id',
        'user_id',
        'oncard_user_id',
        'oncard_account_number',
        'nama',
        'user_type',
        'sync_date',
        'detail',
    ];

    protected $casts = [
        'sync_date' => 'date',
        'detail' => 'array',
    ];

    /**
     * Define relationships
     */

    // Agency relationship
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function rules($id = null)
    {
        return [
            'agency_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:agency,id|integer',
            'oncard_instansi_id' => (empty($id) ? 'required|' : 'nullable|') . 'integer',
            'user_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:users,id|integer',
            'oncard_user_id' => 'nullable|integer',
            'oncard_account_number' => 'nullable|integer',
            'nama' => (empty($id) ? 'required|' : 'nullable|') . 'string|max:255',
            'user_type' => (empty($id) ? 'required|' : 'nullable|') . 'in:siswa,general,merchant,agency,owner',
            'sync_date' => 'nullable|date',
            'detail' => 'nullable|string',
        ];
    }
}

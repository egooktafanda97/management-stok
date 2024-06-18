<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi plural
    protected $table = 'payment_types';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'agency_id',
        'gudang_id',
        'name',
        'type',
        'props',
        'description',
        'icon',
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

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public static function rules($id = null)
    {
        return [
            'agency_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:agency,id|integer',
            'gudang_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:gudang,id|integer',
            'name' => (empty($id) ? 'required|' : 'nullable|') . 'string|max:255',
            'type' => (empty($id) ? 'required|' : 'nullable|') . 'string|max:255',
            'props' => 'nullable|string',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'status_id' => (empty($id) ? 'required|' : 'nullable|') . 'exists:status,id|integer',
        ];
    }
}

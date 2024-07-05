<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyvendorpayment extends Model
{
    use HasFactory;
    protected $table = 'keyvendorpayments';

    protected $fillable = [
        'agency_id',
        'gudang_id',
        'app',
        'user_gudang_id',
        'apikeys',
        'status_id',
    ];

    // Define the relationship with the Agency model
    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    // Define the relationship with the Gudang model
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    // Define the relationship with the User (Gudang User) model
    public function userGudang()
    {
        return $this->belongsTo(User::class, 'user_gudang_id');
    }

    // Define the relationship with the Status model
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}

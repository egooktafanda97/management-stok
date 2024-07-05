<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'status_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // relation to toko and kasir polimorph
    public function actor()
    {
        return $this->morphTo();
    }

    // relation to status
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    // rule
    public static function rules($id = null)
    {
        return [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'status_id' => 'required|integer',
        ];
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    // relation to agency
    public function agency()
    {
        return $this->hasOne(Agency::class);
    }

    // relation to kasir

    public function kasir()
    {
        return $this->hasOne(Kasir::class);
    }

    // relation to gudang

    public function gudang()
    {
        return $this->hasOne(Gudang::class, 'user_id');
    }

    // set entry password by bcrypt
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }
}

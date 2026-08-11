<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ ADD THIS
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
 
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // ✅ Add HasRoles trait

    protected $fillable = [
        'name',
        'email',
        'phone',
        'id_no',
        'role',
        'role_id',
        'store',
        'password',
        'one_time_pin',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'one_time_pin',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function generateOneTimePin()
    {
        $otp = rand(100000, 999999);

        $this->one_time_pin = $otp;
        $this->otp_expires_at = now()->addMinutes(10);

        $this->save();

        return $otp;
    }

    public function verifyOneTimePin($otp)
    {
        return $this->one_time_pin === $otp
            && $this->otp_expires_at
            && now()->lessThanOrEqualTo($this->otp_expires_at);
    }

    public function clearOneTimePin()
    {
        $this->one_time_pin = null;
        $this->otp_expires_at = null;

        $this->save();
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
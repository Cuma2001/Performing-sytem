<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'one_time_pin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Generate One Time Pin (OTP)
     */
    public function generateOneTimePin()
    {
        $otp = rand(100000, 999999);

        $this->one_time_pin = $otp;
        $this->otp_expires_at = now()->addMinutes(10);

        $this->save();

        return $otp;
    }

    /**
     * Verify OTP
     */
    public function verifyOneTimePin($otp)
    {
        return $this->one_time_pin === $otp
            && $this->otp_expires_at
            && now()->lessThanOrEqualTo($this->otp_expires_at);
    }

    /**
     * Clear OTP after successful verification
     */
    public function clearOneTimePin()
    {
        $this->one_time_pin = null;
        $this->otp_expires_at = null;

        $this->save();
    }
}
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
    'name',
    'id_no',
    'email',
    'phone',
    'role',
    'store',
    'password',
];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
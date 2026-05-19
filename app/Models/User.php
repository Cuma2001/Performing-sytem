<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'title',
        'first_name',
        'surname',
        'email',
        'mobile_number',
        'id_number',
        'job_title',
        'password',
        'role_id',
        'store_id',
        'communication_preference'
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
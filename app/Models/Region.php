<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}

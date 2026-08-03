<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'region_id', // ✅ keep ONLY this
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'phone',
        'email',
        'manager_name',
        'opening_time',
        'closing_time',
        'is_active',
        'store_type',
        'parent_store_id',
        'manager_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function parentStore()
    {
        return $this->belongsTo(Store::class, 'parent_store_id');
    }

    public function childStores()
    {
        return $this->hasMany(Store::class, 'parent_store_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'store_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getOpeningTimeAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }

    public function getClosingTimeAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }
}
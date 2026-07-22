<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'address',
        'position',
        'department',
        'designation',
        'employment_type',
        'store_id',
        'region_id',
        'user_id',
        'manager_id',
        'hire_date',
        'termination_date',
        'base_salary',
        'commission_rate',
        'bonus_rate',
        'status',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'termination_date' => 'date',
        'base_salary' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'bonus_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }
}

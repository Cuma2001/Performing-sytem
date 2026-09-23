<?php
// app/Models/Target.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Target extends Model
{
    protected $table = 'targets';

    protected $fillable = [
        'employee_id',
        'region_id',
        'store_id',
        'store_code',
        'mtn_code',
        'region_code',
        'year',
        'month',
        'sales_target',
        'quantity_target',
        'revenue_target',
        'customer_target',
        'target_type',
        'quarter',
        'status',
        'achievement_percentage',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sales_target' => 'decimal:2',
        'revenue_target' => 'decimal:2',
        'achievement_percentage' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Store benchmark row this sales-agent target rolls up to, matched on store_code.
     */
    public function storeTarget(): BelongsTo
    {
        return $this->belongsTo(StoreTarget::class, 'store_code', 'store_code');
    }
}

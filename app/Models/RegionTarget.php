<?php
// app/Models/RegionTarget.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionTarget extends Model
{
    protected $table = 'region_targets';

    protected $fillable = [
        'region_code',
        'mtn_code',
        'store_code',
        'store_name',
        'ownership',
        'dealer',
        'store_type',
        'region',
        'cluster',
        'kpi',
        'business_unit',
        'annual_budget',
        'target_jan',
        'target_feb',
        'target_mar',
        'target_apr',
        'target_may',
        'target_jun',
        'target_jul',
        'target_aug',
        'target_sep',
        'target_oct',
        'target_nov',
        'target_dec',
        'target_year',
        'total_target',
        'upload_batch_id',
    ];

    protected $casts = [
        'annual_budget' => 'decimal:2',
        'total_target' => 'decimal:2',
    ];

    public function uploadBatch(): BelongsTo
    {
        return $this->belongsTo(StoreTargetUpload::class, 'upload_batch_id');
    }

    /**
     * Dealer/company target this region rolls up to, matched on mtn_code.
     */
    public function companyTarget()
    {
        return $this->belongsTo(CompanyTarget::class, 'mtn_code', 'mtn_code');
    }

    /**
     * Store target rows under this region.
     */
    public function storeTargets()
    {
        return $this->hasMany(StoreTarget::class, 'region_code', 'region_code');
    }
}

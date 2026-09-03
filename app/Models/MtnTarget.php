<?php
// app/Models/MtnTarget.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MtnTarget extends Model
{
    protected $table = 'mtn_targets';

    protected $fillable = [
        'mtn_code',
        'store_code',
        'ownership',
        'dealer',
        'store_type',
        'region',
        'cluster',
        'kpi',
        'business_unit',
        'annual_budget',
        'target',
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
        'month',
        'upload_batch_id',
    ];

    protected $casts = [
        'target' => 'decimal:2',
        'annual_budget' => 'decimal:2',
        'total_target' => 'decimal:2',
    ];

    public function uploadBatch(): BelongsTo
    {
        return $this->belongsTo(StoreTargetUpload::class, 'upload_batch_id');
    }
}
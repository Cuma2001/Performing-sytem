<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreTarget extends Model
{
    protected $table = 'store_targets';

    protected $fillable = [
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
        'source_file',
        'upload_batch_id',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'annual_budget' => 'decimal:2',
        'target_year' => 'integer',
    ];

    public function uploadBatch(): BelongsTo
    {
        return $this->belongsTo(StoreTargetUpload::class, 'upload_batch_id');
    }
}

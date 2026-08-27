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
        'kpi',
        'target',
        'month',
        'upload_batch_id',
    ];

    protected $casts = [
        'target' => 'decimal:2',
    ];

    public function uploadBatch(): BelongsTo
    {
        return $this->belongsTo(StoreTargetUpload::class, 'upload_batch_id');
    }
}
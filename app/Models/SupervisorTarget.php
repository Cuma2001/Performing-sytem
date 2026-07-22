<?php
// app/Models/SupervisorTarget.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupervisorTarget extends Model
{
    protected $table = 'supervisor_targets';

    protected $fillable = [
        'supervisor_code',
        'store_code',
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
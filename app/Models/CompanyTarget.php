<?php
// app/Models/CompanyTarget.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyTarget extends Model
{
    protected $table = 'company_targets';

    protected $fillable = [
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
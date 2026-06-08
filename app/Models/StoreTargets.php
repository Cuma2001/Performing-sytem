<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreTargets extends Model
{
    //
    use SoftDeletes;

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
        'target_jan' => 'decimal:2',
        'target_feb' => 'decimal:2',
        'target_mar' => 'decimal:2',
        'target_apr' => 'decimal:2',
        'target_may' => 'decimal:2',
        'target_jun' => 'decimal:2',
        'target_jul' => 'decimal:2',
        'target_aug' => 'decimal:2',
        'target_sep' => 'decimal:2',
        'target_oct' => 'decimal:2',
        'target_nov' => 'decimal:2',
        'target_dec' => 'decimal:2',
        'annual_budget' => 'decimal:2',
        'total_target' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Helper method to get target for a specific month
    public function getTargetForMonth(int $month): float
    {
        $monthField = 'target_' . strtolower(date('M', mktime(0, 0, 0, $month, 1)));
        return $this->$monthField ?? 0;
    }

    // Get all monthly targets as an array
    public function getMonthlyTargets(): array
    {
        return [
            1 => $this->target_jan,
            2 => $this->target_feb,
            3 => $this->target_mar,
            4 => $this->target_apr,
            5 => $this->target_may,
            6 => $this->target_jun,
            7 => $this->target_jul,
            8 => $this->target_aug,
            9 => $this->target_sep,
            10 => $this->target_oct,
            11 => $this->target_nov,
            12 => $this->target_dec,
        ];
    }

    // Scopes for filtering
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByKpi($query, $kpi)
    {
        return $query->where('kpi', $kpi);
    }

    public function scopeByBusinessUnit($query, $unit)
    {
        return $query->where('business_unit', $unit);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relationships
    public function performance()
    {
        return $this->hasMany(StorePerformance::class, 'store_target_id');
    }

    public function uploadBatch()
    {
        return $this->belongsTo(StoreTargetUpload::class, 'upload_batch_id');
    }

}

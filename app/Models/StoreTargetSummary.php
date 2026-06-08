<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreTargetSummary extends Model
{
    protected $table = 'store_target_summaries';

    protected $fillable = [
        'region',
        'cluster',
        'kpi',
        'business_unit',
        'year',
        'month',
        'total_target',
        'total_actual',
        'achievement_percentage',
        'store_count',
        'stores_achieving_target',
        'stores_exceeding_target',
        'stores_missing_target',
    ];

    protected $casts = [
        'total_target' => 'decimal:2',
        'total_actual' => 'decimal:2',
        'achievement_percentage' => 'decimal:2',
        'year' => 'integer',
        'month' => 'integer',
        'store_count' => 'integer',
        'stores_achieving_target' => 'integer',
        'stores_exceeding_target' => 'integer',
        'stores_missing_target' => 'integer',
    ];

    protected $appends = ['period_label'];

    /**
     * Get period label (e.g., "January 2026" or "2026 YTD")
     */
    public function getPeriodLabelAttribute(): string
    {
        if ($this->month) {
            return date('F Y', mktime(0, 0, 0, $this->month, 1, $this->year));
        }

        return $this->year . ' YTD';
    }

    /**
     * Get achievement status based on percentage
     */
    public function getAchievementStatusAttribute(): string
    {
        $percentage = $this->achievement_percentage ?? 0;

        if ($percentage >= 100) {
            return 'exceeded';
        } elseif ($percentage >= 90) {
            return 'achieved';
        } elseif ($percentage >= 75) {
            return 'on_track';
        } elseif ($percentage > 0) {
            return 'missed';
        }

        return 'pending';
    }

    /**
     * Get formatted total target
     */
    public function getFormattedTotalTargetAttribute(): string
    {
        return number_format($this->total_target, 2);
    }

    /**
     * Get formatted total actual
     */
    public function getFormattedTotalActualAttribute(): string
    {
        return number_format($this->total_actual, 2);
    }

    /**
     * Get formatted achievement percentage
     */
    public function getFormattedAchievementPercentageAttribute(): string
    {
        return number_format($this->achievement_percentage ?? 0, 2) . '%';
    }

    /**
     * Get stores achieving percentage
     */
    public function getStoresAchievingPercentageAttribute(): float
    {
        if ($this->store_count == 0) {
            return 0;
        }

        return ($this->stores_achieving_target / $this->store_count) * 100;
    }

    /**
     * Get stores exceeding percentage
     */
    public function getStoresExceedingPercentageAttribute(): float
    {
        if ($this->store_count == 0) {
            return 0;
        }

        return ($this->stores_exceeding_target / $this->store_count) * 100;
    }

    /**
     * Get stores missing percentage
     */
    public function getStoresMissingPercentageAttribute(): float
    {
        if ($this->store_count == 0) {
            return 0;
        }

        return ($this->stores_missing_target / $this->store_count) * 100;
    }

    // Scopes
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByKpi($query, $kpi)
    {
        return $query->where('kpi', $kpi);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    public function scopeYtd($query)
    {
        return $query->whereNull('month');
    }

    public function scopeMonthly($query)
    {
        return $query->whereNotNull('month');
    }
}

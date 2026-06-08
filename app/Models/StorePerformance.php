<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorePerformance extends Model
{
    protected $table = 'store_performance';

    protected $fillable = [
        'store_target_id',
        'store_id',
        'store_code',
        'kpi',
        'year',
        'month',
        'target_amount',
        'actual_amount',
        'business_unit',
        'notes',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'achievement_percentage' => 'decimal:2',
        'variance' => 'decimal:2',
        'variance_percentage' => 'decimal:2',
        'year' => 'integer',
        'month' => 'integer',
    ];

    protected $appends = ['status', 'status_label'];

    /**
     * Get the status based on achievement percentage
     */
    public function getStatusAttribute(): string
    {
        $achievement = $this->achievement_percentage ?? 0;

        if ($achievement >= 100) {
            return 'exceeded';
        } elseif ($achievement >= 90) {
            return 'achieved';
        } elseif ($achievement >= 75) {
            return 'on_track';
        } elseif ($achievement > 0) {
            return 'missed';
        }

        return 'pending';
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'exceeded' => 'Exceeded Target',
            'achieved' => 'Achieved Target',
            'on_track' => 'On Track',
            'missed' => 'Missed Target',
            'pending' => 'Pending',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'exceeded' => 'success',
            'achieved' => 'info',
            'on_track' => 'primary',
            'missed' => 'warning',
            'pending' => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get the month name
     */
    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    /**
     * Get the short month name
     */
    public function getShortMonthNameAttribute(): string
    {
        return date('M', mktime(0, 0, 0, $this->month, 1));
    }

    /**
     * Check if target was achieved
     */
    public function isTargetAchieved(): bool
    {
        return ($this->achievement_percentage ?? 0) >= 90;
    }

    /**
     * Check if target was exceeded
     */
    public function isTargetExceeded(): bool
    {
        return ($this->achievement_percentage ?? 0) >= 100;
    }

    /**
     * Get remaining amount to reach target
     */
    public function getRemainingToTarget(): float
    {
        $remaining = $this->target_amount - $this->actual_amount;
        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Get the percentage of target achieved (formatted)
     */
    public function getFormattedAchievement(): string
    {
        return number_format($this->achievement_percentage ?? 0, 2) . '%';
    }

    // Scopes
    public function scopeByYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByMonth($query, int $month)
    {
        return $query->where('month', $month);
    }

    public function scopeByKpi($query, string $kpi)
    {
        return $query->where('kpi', $kpi);
    }

    public function scopeByStoreCode($query, string $storeCode)
    {
        return $query->where('store_code', $storeCode);
    }

    public function scopeAchieved($query)
    {
        return $query->where('achievement_percentage', '>=', 90);
    }

    public function scopeExceeded($query)
    {
        return $query->where('achievement_percentage', '>=', 100);
    }

    public function scopeMissed($query)
    {
        return $query->where('achievement_percentage', '<', 75)
                     ->where('achievement_percentage', '>', 0);
    }

    public function scopePending($query)
    {
        return $query->where('achievement_percentage', 0);
    }

    // Relationships
    public function storeTarget(): BelongsTo
    {
        return $this->belongsTo(StoreTarget::class, 'store_target_id');
    }
}

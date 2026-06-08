<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreTargetUpload extends Model
{
    protected $table = 'store_target_uploads';

    protected $fillable = [
        'filename',
        'original_filename',
        'file_path',
        'file_hash',
        'total_records',
        'processed_records',
        'success_records',
        'failed_records',
        'status',
        'error_log',
        'validation_errors',
        'processing_started_at',
        'processing_completed_at',
        'uploaded_by',
    ];

    protected $casts = [
        'total_records' => 'integer',
        'processed_records' => 'integer',
        'success_records' => 'integer',
        'failed_records' => 'integer',
        'error_log' => 'array',
        'validation_errors' => 'array',
        'processing_started_at' => 'datetime',
        'processing_completed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the upload status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $classes = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_PROCESSING => 'info',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED => 'danger',
            self::STATUS_CANCELLED => 'secondary',
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Get the upload status label
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Check if upload is complete
     */
    public function isComplete(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if upload failed
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_records == 0) {
            return 0;
        }

        return ($this->success_records / $this->total_records) * 100;
    }

    /**
     * Get formatted success rate
     */
    public function getFormattedSuccessRateAttribute(): string
    {
        return number_format($this->success_rate, 2) . '%';
    }

    /**
     * Start processing the upload
     */
    public function startProcessing(): void
    {
        $this->update([
            'status' => self::STATUS_PROCESSING,
            'processing_started_at' => now(),
        ]);
    }

    /**
     * Complete the upload
     */
    public function completeProcessing(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'processing_completed_at' => now(),
        ]);
    }

    /**
     * Fail the upload
     */
    public function failProcessing(string $errorMessage = null): void
    {
        $data = ['status' => self::STATUS_FAILED];

        if ($errorMessage) {
            $errors = $this->error_log ?? [];
            $errors[] = [
                'timestamp' => now()->toISOString(),
                'message' => $errorMessage,
            ];
            $data['error_log'] = $errors;
        }

        $this->update($data);
    }

    /**
     * Add error to the upload log
     */
    public function addError(string $error, array $context = []): void
    {
        $errors = $this->error_log ?? [];
        $errors[] = [
            'timestamp' => now()->toISOString(),
            'message' => $error,
            'context' => $context,
        ];

        $this->update(['error_log' => $errors]);
    }

    /**
     * Add validation error
     */
    public function addValidationError(int $row, string $field, string $message): void
    {
        $errors = $this->validation_errors ?? [];
        $errors[] = [
            'row' => $row,
            'field' => $field,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        $this->update(['validation_errors' => $errors]);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    // Relationships
    public function storeTargets(): HasMany
    {
        return $this->hasMany(StoreTarget::class, 'upload_batch_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

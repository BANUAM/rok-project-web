<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LearningLesson extends Model
{
    protected $fillable = [
        'learning_module_id',
        'title',
        'content_type',
        'video_url',
        'pdf_path',
        'pdf_filename',
        'file_size',
        'duration',
        'summary',
        'content',
        'is_free_preview',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_free_preview' => 'boolean',
            'sort_order' => 'integer',
            'file_size' => 'integer',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(LearningModule::class, 'learning_module_id');
    }

    public function isPdf(): bool
    {
        return $this->content_type === 'pdf' || !empty($this->pdf_path);
    }

    public function isVideo(): bool
    {
        return $this->content_type === 'video' || (!empty($this->video_url) && empty($this->pdf_path));
    }

    public function getPdfUrlAttribute(): ?string
    {
        if (empty($this->pdf_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->pdf_path);
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return '';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($this->file_size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 1) . ' ' . $units[$pow];
    }
}

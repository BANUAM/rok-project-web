<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningLesson extends Model
{
    protected $fillable = [
        'learning_module_id',
        'title',
        'video_url',
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
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(LearningModule::class, 'learning_module_id');
    }
}

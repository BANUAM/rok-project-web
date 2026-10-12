<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class LearningModule extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'thumbnail_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(LearningLesson::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path && Storage::disk('public')->exists($this->thumbnail_path)) {
            return Storage::url($this->thumbnail_path);
        }
        return asset('img/dummy-module.svg');
    }
}

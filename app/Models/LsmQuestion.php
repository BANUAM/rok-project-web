<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class LsmQuestion extends Model
{
    protected $fillable = [
        'lsm_id',
        'question',
        'image_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function lsm(): BelongsTo
    {
        return $this->belongsTo(Lsm::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(LsmOption::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}

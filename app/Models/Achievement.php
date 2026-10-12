<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Achievement extends Model
{
    protected $fillable = [
        'division_id',
        'title',
        'team_name',
        'image_path',
        'caption',
        'event_year',
        'event_date',
        'is_highlight',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_highlight' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'event_year' => 'integer',
            'event_date' => 'date',
        ];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path);
        }
        return asset('img/dummy-achievement.svg');
    }
}

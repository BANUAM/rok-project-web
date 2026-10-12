<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Division extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'game_name',
        'icon_path',
        'banner_path',
        'description',
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

    public function players(): HasMany
    {
        return $this->hasMany(Player::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activePlayers(): HasMany
    {
        return $this->hasMany(Player::class)
            ->where('is_active', true)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    public function getIconUrlAttribute(): string
    {
        if ($this->icon_path && Storage::disk('public')->exists($this->icon_path)) {
            return Storage::url($this->icon_path);
        }
        return asset('img/dummy-division.svg');
    }
}

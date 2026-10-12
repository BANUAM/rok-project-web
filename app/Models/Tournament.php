<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'format',
        'banner_path',
        'status',
        'organizer_name',
        'tier_required',
        'description',
        'rules',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(TournamentTeam::class)->orderBy('seed')->orderBy('id');
    }

    public function matches(): HasMany
    {
        return $this->hasMany(TournamentMatch::class)->orderBy('round_number')->orderBy('match_number');
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->banner_path && Storage::disk('public')->exists($this->banner_path)) {
            return Storage::url($this->banner_path);
        }
        return asset('img/dummy-tournament.svg');
    }
}

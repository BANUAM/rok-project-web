<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Tournament extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'format',
        'team_count',
        'banner_path',
        'status',
        'organizer_name',
        'tier_required',
        'description',
        'rules',
        'bracket_state',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'bracket_state' => 'array',
            'is_public' => 'boolean',
            'team_count' => 'integer',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class TrialCandidate extends Model
{
    protected $fillable = [
        'name',
        'email',
        'access_token',
        'token_expires_at',
        'nickname',
        'address',
        'age',
        'birthplace',
        'birthdate',
        'role',
        'ml_tier_id',
        'stars',
        'profile_screenshot_path',
        'favorite_hero_screenshot_path',
        'tier_screenshot_path',
        'status',
        'profile_completed_at',
        'lsm_completed_at',
        'reviewed_at',
        'reviewer_notes',
        'contract_completed',
        'created_player_id',
    ];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'birthdate' => 'date',
            'age' => 'integer',
            'stars' => 'integer',
            'profile_completed_at' => 'datetime',
            'lsm_completed_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'contract_completed' => 'boolean',
        ];
    }

    public function mlTier(): BelongsTo
    {
        return $this->belongsTo(MlTier::class);
    }

    public function createdPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'created_player_id');
    }

    public function lsmAttempt(): HasOne
    {
        return $this->hasOne(LsmAttempt::class);
    }

    public function getProfileScreenshotUrlAttribute(): ?string
    {
        return $this->profile_screenshot_path ? Storage::url($this->profile_screenshot_path) : null;
    }

    public function getFavoriteHeroScreenshotUrlAttribute(): ?string
    {
        return $this->favorite_hero_screenshot_path ? Storage::url($this->favorite_hero_screenshot_path) : null;
    }

    public function getTierScreenshotUrlAttribute(): ?string
    {
        return $this->tier_screenshot_path ? Storage::url($this->tier_screenshot_path) : null;
    }

    public function getRankDisplayAttribute(): string
    {
        $tierName = $this->mlTier?->name ?? 'Unranked';
        return "{$tierName} — {$this->stars} Stars";
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'registered' => 'Pendaftaran Awal',
            'profile_completed' => 'Profil Terisi',
            'lsm_completed' => 'LSM Selesai',
            'under_review' => 'Sedang Ditinjau',
            'passed' => 'Lolos Seleksi',
            'rejected' => 'Tidak Lolos',
            default => ucfirst($this->status),
        };
    }
}

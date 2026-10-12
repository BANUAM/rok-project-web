<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TournamentTeam extends Model
{
    protected $fillable = [
        'tournament_id',
        'team_name',
        'logo_path',
        'whatsapp_number',
        'seed',
    ];

    protected function casts(): array
    {
        return [
            'seed' => 'integer',
        ];
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return Storage::url($this->logo_path);
        }
        return asset('img/dummy-team-logo.svg');
    }
}

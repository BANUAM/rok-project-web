<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentMatch extends Model
{
    protected $fillable = [
        'tournament_id',
        'round_number',
        'round_name',
        'match_number',
        'team1_id',
        'team2_id',
        'score1',
        'score2',
        'winner_id',
        'status',
        'scheduled_time',
    ];

    protected function casts(): array
    {
        return [
            'round_number' => 'integer',
            'match_number' => 'integer',
            'score1' => 'integer',
            'score2' => 'integer',
            'scheduled_time' => 'datetime',
        ];
    }

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function team1(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'team1_id');
    }

    public function team2(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'team2_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(TournamentTeam::class, 'winner_id');
    }
}

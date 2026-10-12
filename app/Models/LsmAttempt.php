<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LsmAttempt extends Model
{
    protected $fillable = [
        'lsm_id',
        'trial_candidate_id',
        'total_questions',
        'correct_answers',
        'score',
        'is_passed',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_questions' => 'integer',
            'correct_answers' => 'integer',
            'score' => 'float',
            'is_passed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function lsm(): BelongsTo
    {
        return $this->belongsTo(Lsm::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(TrialCandidate::class, 'trial_candidate_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(LsmAttemptAnswer::class);
    }
}

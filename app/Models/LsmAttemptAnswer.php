<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LsmAttemptAnswer extends Model
{
    protected $fillable = [
        'lsm_attempt_id',
        'lsm_question_id',
        'selected_option_id',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(LsmAttempt::class, 'lsm_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(LsmQuestion::class, 'lsm_question_id');
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(LsmOption::class, 'selected_option_id');
    }
}

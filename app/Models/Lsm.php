<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lsm extends Model
{
    protected $table = 'ls_ms';

    protected $fillable = [
        'title',
        'description',
        'question_limit',
        'duration_minutes',
        'passing_score',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'question_limit' => 'integer',
            'duration_minutes' => 'integer',
            'passing_score' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(LsmQuestion::class)->orderBy('sort_order')->orderBy('id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(LsmAttempt::class);
    }
}

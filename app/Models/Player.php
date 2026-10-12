<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Player extends Model
{
    protected $fillable = [
        'division_id',
        'user_id',
        'real_name',
        'nickname',
        'domicile',
        'age',
        'birthplace',
        'birthdate',
        'role',
        'photo_path',
        'status',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'birthdate' => 'date',
            'age' => 'integer',
        ];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            return Storage::url($this->photo_path);
        }
        return asset('img/dummy-player.svg');
    }

    public function isManagement(): bool
    {
        return in_array($this->role, ['Manager', 'Coach', 'Analyst']);
    }
}

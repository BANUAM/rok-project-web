<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MlTier extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}

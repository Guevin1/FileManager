<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthTokens extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'token',
        'perms',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'token' => 'string',
        ];
    }
}

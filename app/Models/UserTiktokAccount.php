<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTiktokAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'nickname',
        'avatar_url',
        'verification_code',
        'is_verified',
        'verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this TikTok account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

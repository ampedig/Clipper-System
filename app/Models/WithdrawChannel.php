<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WithdrawChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'fee',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'fee' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'withdraw_channel_id');
    }
}

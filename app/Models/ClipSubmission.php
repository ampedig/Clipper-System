<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClipSubmission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'clip_campaign_id',
        'user_id',
        'submitted_url',
        'video_id',
        'current_views',
        'credited_views',
        'total_earned',
        'status',
        'is_reference',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_reference' => 'boolean',
            'current_views' => 'integer',
            'credited_views' => 'integer',
            'total_earned' => 'integer',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include submissions set as reference with valid status.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeAsReference($query)
    {
        return $query->where('is_reference', true)
            ->whereIn('status', ['approved', 'active', 'completed']);
    }

    /**
     * Get the user that owns the submission.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the campaign that owns the submission.
     */
    public function clipCampaign()
    {
        return $this->belongsTo(ClipCampaign::class);
    }
}

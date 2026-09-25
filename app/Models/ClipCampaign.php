<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use Database\Factories\ClipCampaignFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClipCampaign extends Model
{
    /** @use HasFactory<ClipCampaignFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (ClipCampaign $campaign): void {
            if (empty($campaign->slug)) {
                $campaign->slug = static::generateUniqueSlug($campaign->title);
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'thumbnail',
        'title',
        'slug',
        'description',
        'brief',
        'source_url',
        'commission_amount',
        'view_threshold',
        'view_max',
        'clipper_limit',
        'start_at',
        'end_at',
        'status',
        'created_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'commission_amount' => 'integer',
            'view_threshold' => 'integer',
            'view_max' => 'integer',
            'clipper_limit' => 'integer',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'status' => CampaignStatus::class,
        ];
    }

    /**
     * Mendapatkan user pembuat / creator dari kampanye ini.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope query untuk memfilter kampanye yang sedang aktif.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', CampaignStatus::Active);
    }

    /**
     * Scope query untuk memfilter kampanye yang berstatus segera (upcoming).
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->where('status', CampaignStatus::Upcoming);
    }

    /**
     * Scope query untuk memfilter kampanye yang telah selesai (completed).
     */
    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', CampaignStatus::Completed);
    }

    /**
     * Mendapatkan URL thumbnail kampanye atau avatar inisial teks jika thumbnail kosong.
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->thumbnail && Storage::disk('public')->exists($this->thumbnail)) {
                    return Storage::url($this->thumbnail);
                }

                $title = $this->title ? urlencode($this->title) : 'Campaign';

                return "https://ui-avatars.com/api/?name={$title}&background=3b82f6&color=fff&bold=true";
            }
        );
    }

    /**
     * Menghasilkan slug unik untuk kampanye clip.
     */
    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Mendapatkan nama route key untuk model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the clip submissions for the campaign.
     */
    public function clipSubmissions()
    {
        return $this->hasMany(ClipSubmission::class);
    }
}

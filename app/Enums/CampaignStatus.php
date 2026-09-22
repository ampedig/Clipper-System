<?php

namespace App\Enums;

/**
 * Status siklus hidup kampanye clip.
 */
enum CampaignStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Upcoming = 'upcoming';
    case Completed = 'completed';

    /**
     * Mendapatkan label human-readable dalam Bahasa Indonesia.
     */
    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Inactive => 'Ditutup',
            self::Upcoming => 'Segera',
            self::Completed => 'Selesai',
        };
    }

    /**
     * Mendapatkan class CSS badge untuk rendering UI dashboard.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Active => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
            self::Inactive => 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400',
            self::Upcoming => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
            self::Completed => 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
        };
    }

    /**
     * Mendapatkan class ikon FontAwesome untuk status.
     */
    public function iconClass(): string
    {
        return match ($this) {
            self::Active => 'fa-solid fa-circle-check',
            self::Inactive => 'fa-solid fa-circle-xmark',
            self::Upcoming => 'fa-solid fa-clock',
            self::Completed => 'fa-solid fa-flag-checkered',
        };
    }
}

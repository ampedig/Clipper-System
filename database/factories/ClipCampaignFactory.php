<?php

namespace Database\Factories;

use App\Enums\CampaignStatus;
use App\Models\ClipCampaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClipCampaign>
 */
class ClipCampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(3),
            'brief' => fake()->paragraphs(2, true),
            'source_url' => fake()->url(),
            'commission_amount' => fake()->randomElement([5000, 10000, 15000, 25000, 50000]),
            'view_threshold' => fake()->randomElement([1000, 2000, 5000, 10000]),
            'view_max' => fake()->randomElement([50000, 100000, 200000, null]),
            'clipper_limit' => fake()->randomElement([50, 100, 150, 200, null]),
            'start_at' => now()->subDays(fake()->numberBetween(1, 10)),
            'end_at' => now()->addDays(fake()->numberBetween(15, 60)),
            'status' => CampaignStatus::Active,
            'created_by' => User::factory(),
        ];
    }

    /**
     * State untuk kampanye aktif.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampaignStatus::Active,
            'start_at' => now()->subDays(5),
            'end_at' => now()->addDays(30),
        ]);
    }

    /**
     * State untuk kampanye yang akan datang (upcoming).
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampaignStatus::Upcoming,
            'start_at' => now()->addDays(7),
            'end_at' => now()->addDays(37),
        ]);
    }

    /**
     * State untuk kampanye yang telah selesai (completed).
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampaignStatus::Completed,
            'start_at' => now()->subDays(40),
            'end_at' => now()->subDays(5),
        ]);
    }

    /**
     * State untuk kampanye nonaktif (inactive).
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CampaignStatus::Inactive,
        ]);
    }
}

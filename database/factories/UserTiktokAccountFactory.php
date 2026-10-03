<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserTiktokAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserTiktokAccount>
 */
class UserTiktokAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'username' => fake()->unique()->userName(),
            'nickname' => fake()->name(),
            'avatar_url' => fake()->imageUrl(200, 200, 'people'),
            'verification_code' => (string) fake()->numberBetween(100000, 999999),
            'is_verified' => false,
            'verified_at' => null,
        ];
    }

    /**
     * Indicate that the TikTok account is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }
}

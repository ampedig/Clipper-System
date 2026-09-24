<?php

namespace Database\Factories;

use App\Models\WithdrawChannel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WithdrawChannel>
 */
class WithdrawChannelFactory extends Factory
{
    protected $model = WithdrawChannel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Bank',
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'fee' => fake()->randomElement([0, 2500, 4500, 6500]),
            'is_active' => true,
        ];
    }
}

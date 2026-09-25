<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WalletTransaction>
 */
class WalletTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->numberBetween(10000, 100000);
        $balanceBefore = fake()->numberBetween(0, 500000);

        return [
            'user_id' => User::factory(),
            'type' => 'credit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $amount,
            'notes' => fake()->sentence(),
            'created_at' => now(),
        ];
    }

    /**
     * Indicate that the transaction is a debit.
     */
    public function debit(): static
    {
        return $this->state(function (array $attributes) {
            $amount = $attributes['amount'] ?? 20000;
            $balanceBefore = max($attributes['balance_before'] ?? 100000, $amount + 10000);

            return [
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceBefore - $amount,
            ];
        });
    }
}

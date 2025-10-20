<?php

namespace Database\Factories;

use App\Models\Receivable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receivable>
 */
class ReceivableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement([
            Receivable::STATUS_EXPECTED,
            Receivable::STATUS_RECEIVED,
            Receivable::STATUS_OVERDUE,
        ]);

        $dueDate = match ($status) {
            Receivable::STATUS_EXPECTED => Carbon::now()->addDays($this->faker->numberBetween(3, 60)),
            Receivable::STATUS_RECEIVED => Carbon::now()->subDays($this->faker->numberBetween(1, 45)),
            Receivable::STATUS_OVERDUE => Carbon::now()->subDays($this->faker->numberBetween(2, 30)),
        };

        $receivedAt = $status === Receivable::STATUS_RECEIVED
            ? $dueDate->copy()->addDays($this->faker->numberBetween(0, 5))->setTimeFromTimeString('10:00')
            : null;

        $isRecurring = $this->faker->boolean(20);

        return [
            'user_id' => User::factory(),
            'title' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->optional(0.5)->paragraph(),
            'amount' => $this->faker->randomFloat(2, 100, 12000),
            'due_date' => $dueDate,
            'reminder_date' => $status === Receivable::STATUS_EXPECTED
                ? $dueDate->copy()->subDays($this->faker->numberBetween(1, 7))
                : null,
            'status' => $status,
            'received_at' => $receivedAt,
            'is_recurring' => $isRecurring,
            'recurrence_interval' => $isRecurring
                ? $this->faker->randomElement(['weekly', 'monthly', 'quarterly', 'yearly'])
                : null,
        ];
    }
}

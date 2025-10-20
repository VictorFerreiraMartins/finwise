<?php

namespace Database\Factories;

use App\Models\Payable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payable>
 */
class PayableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement([
            Payable::STATUS_PENDING,
            Payable::STATUS_PAID,
            Payable::STATUS_OVERDUE,
        ]);

        $dueDate = match ($status) {
            Payable::STATUS_PENDING => Carbon::now()->addDays($this->faker->numberBetween(1, 60)),
            Payable::STATUS_PAID => Carbon::now()->subDays($this->faker->numberBetween(1, 30)),
            Payable::STATUS_OVERDUE => Carbon::now()->subDays($this->faker->numberBetween(5, 60)),
        };

        $paidAt = $status === Payable::STATUS_PAID
            ? $dueDate->copy()->addDays($this->faker->numberBetween(0, 3))->setTimeFromTimeString('09:00')
            : null;

        $isRecurring = $this->faker->boolean(25);

        return [
            'user_id' => User::factory(),
            'title' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->optional(0.6)->paragraph(),
            'amount' => $this->faker->randomFloat(2, 50, 5000),
            'due_date' => $dueDate,
            'reminder_date' => $status === Payable::STATUS_PENDING
                ? $dueDate->copy()->subDays($this->faker->numberBetween(1, 5))
                : null,
            'status' => $status,
            'paid_at' => $paidAt,
            'is_recurring' => $isRecurring,
            'recurrence_interval' => $isRecurring
                ? $this->faker->randomElement(['weekly', 'monthly', 'quarterly', 'yearly'])
                : null,
        ];
    }
}

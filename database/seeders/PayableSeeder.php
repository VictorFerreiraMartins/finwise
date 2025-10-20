<?php

namespace Database\Seeders;

use App\Models\Payable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PayableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'FinWise Admin',
            'email' => 'admin@finwise.test',
        ]);

        Payable::factory()
            ->count(8)
            ->for($user)
            ->create();

        Payable::factory()
            ->for($user)
            ->state(function () {
                $dueDate = Carbon::now()->startOfMonth()->addMonth()->addDays(2);

                return [
                    'title' => 'Aluguel do apartamento',
                    'description' => 'Parcela mensal do contrato de locação.',
                    'amount' => 2450.00,
                    'due_date' => $dueDate,
                    'reminder_date' => $dueDate->copy()->subDays(5),
                    'status' => Payable::STATUS_PENDING,
                    'paid_at' => null,
                    'is_recurring' => true,
                    'recurrence_interval' => 'monthly',
                ];
            })
            ->create();

        Payable::factory()
            ->for($user)
            ->state(function () {
                $dueDate = Carbon::now()->subDays(7);

                return [
                    'title' => 'Conta de energia',
                    'description' => 'Fatura de energia elétrica referente ao último mês.',
                    'amount' => 320.75,
                    'due_date' => $dueDate,
                    'reminder_date' => $dueDate->copy()->subDays(3),
                    'status' => Payable::STATUS_OVERDUE,
                    'paid_at' => null,
                    'is_recurring' => false,
                    'recurrence_interval' => null,
                ];
            })
            ->create();
    }
}

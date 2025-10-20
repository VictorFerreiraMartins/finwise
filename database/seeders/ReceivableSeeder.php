<?php

namespace Database\Seeders;

use App\Models\Receivable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReceivableSeeder extends Seeder
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

        Receivable::factory()
            ->count(6)
            ->for($user)
            ->create();

        Receivable::factory()
            ->for($user)
            ->state(function () {
                $dueDate = Carbon::now()->addDays(10);

                return [
                    'title' => 'Recebimento de aluguel',
                    'description' => 'Receita recorrente do imóvel comercial.',
                    'amount' => 3800.00,
                    'due_date' => $dueDate,
                    'reminder_date' => $dueDate->copy()->subDays(4),
                    'status' => Receivable::STATUS_EXPECTED,
                    'received_at' => null,
                    'is_recurring' => true,
                    'recurrence_interval' => 'monthly',
                ];
            })
            ->create();

        Receivable::factory()
            ->for($user)
            ->state(function () {
                $dueDate = Carbon::now()->subDays(3);

                return [
                    'title' => 'Venda de consultoria',
                    'description' => 'Pagamento referente à consultoria prestada ao cliente XPTO.',
                    'amount' => 2100.00,
                    'due_date' => $dueDate,
                    'reminder_date' => $dueDate->copy()->subDays(5),
                    'status' => Receivable::STATUS_OVERDUE,
                    'received_at' => null,
                    'is_recurring' => false,
                    'recurrence_interval' => null,
                ];
            })
            ->create();
    }
}

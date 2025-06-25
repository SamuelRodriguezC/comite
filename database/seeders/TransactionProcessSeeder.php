<?php

namespace Database\Seeders;

use App\Models\Process;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionProcessSeeder extends Seeder
{
    public function run(): void
    {
        Transaction::factory(100)->create()->each(function ($transaction) {
            // Etapas únicas del 1 al 3
            $stages = [1, 2];
            foreach ($stages as $stage) {
                Process::factory()->create([
                    'requirement' => '',
                    'state' => fake()->numberBetween(1, 7),
                    'comment' => '',
                    'completed' => false,
                    'delivery_date' => null,
                    'transaction_id' => $transaction->id,
                    'stage_id' => $stage,
                ]);
            }
        });
    }
}

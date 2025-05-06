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
        // Creamos, por ejemplo, 10 transacciones
        Transaction::factory(30)->create()->each(function ($transaction) {
            // Etapas únicas del 1 al 3
            $stages = [1, 2, 3];
            foreach ($stages as $stage) {
                Process::factory()->create([
                    'requirement' => '',
                    'state' => 3,
                    'comment' => '',
                    'completed' => false,
                    'transaction_id' => $transaction->id,
                    'stage_id' => $stage,
                ]);
            }
        });
    }
}

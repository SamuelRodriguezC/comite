<?php

namespace Database\Seeders;

use App\Models\Signer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SignerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Signer::factory(5)->create();
    }
}

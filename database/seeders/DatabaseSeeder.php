<?php

namespace Database\Seeders;

use App\Models\Process;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Groups the seeders that will be called in the migrations
     */
    public function run(): void
    {
        $this->call([
            CourseSeeder::class,
            ConceptSeeder::class,
            DocumentSeeder::class,
            OptionSeeder::class,
            RoleSeeder::class,
            StageSeeder::class,
            UserSeeder::class,
            //Esta clase debe ejecutarse luego hacer los seeders
            // php artisan db:seed --class=ProfileTransactionSeeder
            // ProfileTransactionSeeder::class,
        ]);
        Process::factory(50)->create();
    }
}
/**
 * User::factory()->create(['name' => 'Test User', 'email' => 'test@ example.com',]);
 * User::factory(10)->create();
 */

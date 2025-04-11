<?php

namespace Database\Seeders;

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
            DocumentSeeder::class,
            OptionSeeder::class,
            StageSeeder::class,
        ]);
    }
}
/**
 * User::factory()->create(['name' => 'Test User', 'email' => 'test@ example.com',]);
 * User::factory(10)->create();
 */

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Process;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Groups the seeders that will be called in the migrations
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            CourseSeeder::class,
            ConceptSeeder::class,
            DocumentSeeder::class,
            OptionSeeder::class,
            RoleSeeder::class,
            StageSeeder::class,
            UserSeeder::class,
            RoleHasPermissionsSeeder::class,
            //Esta clase debe ejecutarse luego hacer los seeders
            // php artisan db:seed --class=ProfileTransactionSeeder
            // ProfileTransactionSeeder::class,
        ]);
        Process::factory(30)->create();
        Profile::factory(30)->create();
    }
}


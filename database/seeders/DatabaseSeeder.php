<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Process;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use App\Models\Signer;
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
            RoleHasPermissionsSeeder::class,
            UserSeeder::class,
            RubricSeeder::class,
            // TransactionProcessSeeder::class,
            //Esta clase debe ejecutarse luego hacer los seeders
            // php artisan db:seed --class=ProfileTransactionSeeder
            // ProfileTransactionSeeder::class,
        ]);
        // Profile::factory(50)->create();

    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PassportSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        Artisan::call("optimize:clear");
    }
}

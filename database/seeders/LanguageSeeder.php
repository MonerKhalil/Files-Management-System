<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Language::query()->truncate();
        Schema::enableForeignKeyConstraints();
        Language::query()->create([
            "code" => "en",
            "name" => "English",
            "default" => true,
            "denied_from_delete" => true,
        ]);
        Language::query()->create([
            "code" => "ar",
            "name" => "العربية",
            "default" => true,
            'isRTL' => true,
            "denied_from_delete" => true,
        ]);
    }
}

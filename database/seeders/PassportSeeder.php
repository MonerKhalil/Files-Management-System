<?php

namespace Database\Seeders;

use App\Helpers\ClassesProcess\RolesPermissions\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('passport:keys');
        Artisan::call('passport:client', ['--personal' => true, '--name' => config('app.name') . ' Personal Access Client']);
        $provider = in_array('users', array_keys(config('auth.providers'))) ? 'users' : null;
        Artisan::call('passport:client', ['--password' => true, '--name' => config('app.name') . ' Password Grant Client', '--provider' => $provider]);
    }
}

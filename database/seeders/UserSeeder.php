<?php

namespace Database\Seeders;

use App\Helpers\ClassesProcess\RolesPermissions\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::query()->truncate();
        Schema::enableForeignKeyConstraints();
        $role = Role::query()->where("name",Roles::ADMIN)->first();
        if (!is_null($role)){
            $user = User::query()->create([
                "name" => "moner khalil",
                "first_name" => "moner",
                "last_name" => "khalil",
                "email" => "monerkhalil90@gmail.com",
                "password" => Hash::make("123123123"),
                "phone" => "0937341826",
                "email_verified_at" => now(),
                "role_id" => $role->id,
                "denied_from_delete" => true,
            ]);
            $user->addRole($role->id);
        }
    }
}

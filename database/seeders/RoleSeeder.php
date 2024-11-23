<?php

namespace Database\Seeders;

use App\Helpers\MyApp;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Permission::query()->truncate();
        Role::query()->truncate();
        Schema::enableForeignKeyConstraints();
        MyApp::Classes()->permissionsMap->createPermissionsInDB();
        MyApp::Classes()->permissionsMap->createRolesInDB();
    }
}

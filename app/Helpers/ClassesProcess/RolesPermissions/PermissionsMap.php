<?php

namespace App\Helpers\ClassesProcess\RolesPermissions;

use App\Helpers\MyApp;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class PermissionsMap
{
    private $constVars = null;

    const READ = "read_index";
    const READ_TRASHES = "read_trashes";
    const CREATE = "create";
    const UPDATE = "update";
    const DELETE = "delete";
    const FORCE_DELETE = "force_delete";
    const RESTORE = "restore";
    const Export = "export";
    const All = "all";

    public function getMapPermissions(): array{
        if (is_null($this->constVars)){
            $oClass = new ReflectionClass(__CLASS__);
            $this->constVars = $oClass->getConstants();
        }
        return $this->constVars;
    }

    public function createPermissionsInDB(){
        $permissions = require __DIR__ . "/arr_permissions.php";
        $final = [];
        foreach ($permissions as $permission) {
            $final[] = [
                "name" => $permission,
                "display_name" => MyApp::Classes()->stringProcess->camelCase($permission),
                "description" => MyApp::Classes()->stringProcess->camelCase($permission),
                "created_at" => now()->toDateTimeString(),
                "updated_at" => now()->toDateTimeString(),
            ];
        }
        DB::table("permissions")->insert($final);
    }

    public function createRolesInDB(){
        $roles = require __DIR__ . "/arr_roles.php";
        foreach ($roles as $role => $permissions) {
            $role = Role::create([
                "name" => $role,
                "display_name" => MyApp::Classes()->stringProcess->camelCase($role),
                "description" => MyApp::Classes()->stringProcess->camelCase($role),
                "denied_from_delete" => true,
            ]);
            if (sizeof($permissions) > 0){
                $role->syncPermissions($permissions);
            }
        }
    }

    public function createPermissionsMapTable(string $table){
        $permissions = [];
        foreach ($this->getMapPermissions() as $permission){
            $permissions[] = "{$permission}_{$table}";
        }
        return $permissions;
    }

    public function createPermissionsMapTableExcept(string $table,...$process){
        $permissions = [];
        foreach ($this->getMapPermissions() as $permission){
            if (!in_array($permission,$process)){
                $permissions[] = "{$permission}_{$table}";
            }
        }
        return $permissions;
    }

    public function createPermissionsMapTableOnly(string $table,...$process){
        $permissions = [];
        foreach ($this->getMapPermissions() as $permission){
            if (in_array($permission,$process)){
                $permissions[] = "{$permission}_{$table}";
            }
        }
        return $permissions;
    }
}
/*
$user->assignRole('writer');
$user->removeRole('writer');
$user->syncRoles(params);
$role->givePermissionTo('edit articles');
$role->revokePermissionTo('edit articles');
$role->syncPermissions(params);
$permission->assignRole('writer');
$permission->removeRole('writer');
$permission->syncRoles(params);
 * */

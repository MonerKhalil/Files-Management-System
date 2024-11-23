<?php

namespace App\Helpers\ClassesProcess\RolesPermissions;

trait TPermissions
{
    private ?array $permissions = null;

    public function setPermissionsUserAuth(){
        $user = $this->get();
        $this->permissions = !is_null($user) ? $user->allPermissions(["name"])->pluck("name")->toArray() : [];
    }

    public function getPermissions(): array{
        return $this->permissions ?? [];
    }

    public function checkPermissionExists(string|array $permission ,bool $typeConditionOr = true): bool{
        if (!is_array($permission)){
            $permission = [$permission];
        }
        $temp = 0;
        foreach ($permission as $name){
            if (in_array($name,$this->permissions)){
                if ($typeConditionOr){
                    return true;
                }
                $temp++;
            }
        }
        return ( !$typeConditionOr && (count($permission) == $temp) );
    }

    public function addPermissions($permission){
        if (!is_array($permission)){
            $permission = [$permission];
        }
        $this->permissions = array_merge($this->permissions,$permission);
    }
}

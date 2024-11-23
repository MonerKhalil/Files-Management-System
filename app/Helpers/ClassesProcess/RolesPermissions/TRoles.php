<?php

namespace App\Helpers\ClassesProcess\RolesPermissions;

trait TRoles
{
    private ?array $roles = null;

    public function setRolesUserAuth(){
        $user = $this->get();
        $this->roles = !is_null($user) ? $user->roles()->pluck("name")->toArray() : [];
    }

    public function getRoles(): array{
        return $this->roles ?? [];
    }

    public function checkRoleExists(string|array $role ,bool $typeConditionOr = true): bool{
        if (!is_array($role)){
            $role = [$role];
        }
        $temp = 0;
        foreach ($role as $name){
            if (in_array($name,$this->roles)){
                if ($typeConditionOr){
                    return true;
                }
                $temp++;
            }
        }
        return ( !$typeConditionOr && (count($role) == $temp) );
    }

    public function addRoles($role){
        if (!is_array($role)){
            $role = [$role];
        }
        $this->roles = array_merge($this->roles,$role);
    }
}

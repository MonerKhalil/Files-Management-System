<?php

namespace App\DTO;

use App\Helpers\ClassesBase\BaseDTO;
use App\Helpers\ClassesProcess\RolesPermissions\Roles;
use App\Helpers\MyApp;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserDTO extends BaseDTO
{
    public $image = null;
    public ?string $name = null,
                    $first_name = null,
                    $last_name = null,
                    $email = null,
                    $password = null,
                    $phone = null,
                    $role_id = null;

    public function setAttributes()
    {
        $vars = Arr::except(get_object_vars($this),["role_id","image","name"]);
        $this->isNullMulti($vars);
        $this->name = $this->first_name . " " . $this->last_name;
        $this->password = Hash::make($this->password);
        if (is_null($this->role_id)){
            $role = MyApp::Classes()->cacheProcess->getAllRoles(Roles::USER);
            $this->role_id = $role->id;
        }
    }
}

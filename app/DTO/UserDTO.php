<?php

namespace App\DTO;

use App\Exceptions\MainException;
use App\Helpers\ClassesBase\BaseDTO;
use App\Helpers\ClassesProcess\RolesPermissions\Roles;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserDTO extends BaseDTO
{
    private $image = null;
    private ?string $name = null,
                    $first_name = null,
                    $last_name = null,
                    $email = null,
                    $password = null,
                    $phone = null,
                    $role = null;

    public function setAttributes()
    {
        $vars = Arr::except(get_object_vars($this),["role","image","name"]);
        $checkIfAnyNull = $this->isNullMulti($vars);
        if (is_string($checkIfAnyNull)){
            throw ValidationException::withMessages([
                $checkIfAnyNull => __('errors.value_failed'),
            ]);
        }
        $this->name = $this->first_name . " " . $this->last_name;
        $this->password = Hash::make($this->password);
        if (is_null($this->role)){
            $this->role = Roles::USER;
        }
    }
}

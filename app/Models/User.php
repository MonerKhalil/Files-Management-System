<?php

namespace App\Models;

use App\Helpers\ClassesBase\Models\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Passport\HasApiTokens;

class User extends BaseModel implements AuthenticatableContract,AuthorizableContract,CanResetPasswordContract,LaratrustUser
{
    use HasRolesAndPermissions;
    use HasApiTokens, HasFactory, Notifiable;
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verify_token',
        'email_verify_token_expired_at',
    ];

    protected $guarded = ['id'];

    public function role(){
        return $this->belongsTo(Role::class,"role_id","id");
    }

    public function groups_pivot(){
        return $this->hasMany(GroupUser::class,"user_id","id");
    }

    public function groups(){
        return $this->belongsToMany(GroupManager::class,"group_users","user_id","group_id")
            ->withTimestamps();
    }
}

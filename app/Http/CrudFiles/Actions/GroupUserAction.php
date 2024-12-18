<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Helpers\ClassesBase\Routes\RouteAction;
use App\Http\Controllers\CrudControllers\GroupUserController;

class GroupUserAction extends CrudActions
{
    public bool $isActive = false;

    protected function handle():void{
        #code...
        $this->addAction(new RouteAction("show/users/group/{group_id}","show.users.group","showUsersInGroup","get",["show_users_in_group"],[],"id"));
        $this->addAction(new RouteAction("show/add/users/to/group/{group_id}","show.add.users.group","showAddUsersToGroup","get",["add_users_to_group"],[],"id"));
        $this->addAction(new RouteAction("add/users/to/group/{group_id}","add.users.group","addUsersToGroup","post",["add_users_to_group"],[],"id"));
        $this->addAction(new RouteAction("remove/users/to/group/{group_id}","remove.users.group","removeGroupsFromFile","delete",["remove_users_from_group"],[],"id"));
    }

    protected function controller():string{
        return GroupUserController::class;
    }
}

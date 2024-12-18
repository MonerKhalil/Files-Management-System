<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Helpers\ClassesBase\Routes\RouteAction;
use App\Http\Controllers\CrudControllers\GroupFileController;

class GroupFileAction extends CrudActions
{
    public bool $isActive = false;

    protected function handle():void{
        #code...
        #fileActions
        $this->addFileActions();
        $this->addGroupActions();
    }

    protected function controller():string{
        return GroupFileController::class;
    }

    private function addFileActions(){
        $this->addAction(new RouteAction("show/groups/file/{file_id}","show.file.groups","showGroupsFile","get",["show_groups_in_file"],[],"id"));
        $this->addAction(new RouteAction("show/add/groups/to/file/{file_id}","show.add.file.groups","showAddGroupsToFile","get",["add_files_to_group"],[],"id"));
        $this->addAction(new RouteAction("add/groups/to/file/{file_id}","add.file.groups","addGroupsToFile","post",["add_files_to_group"],[],"id"));
        $this->addAction(new RouteAction("remove/groups/file/{file_id}","remove.file.groups","removeGroupsFromFile","delete",["remove_groups_from_file"],[],"id"));
    }

    private function addGroupActions(){
        $this->addAction(new RouteAction("show/files/group/{group_id}","show.group.files","showFilesGroup","get",["show_files_in_group"],[],"id"));
        $this->addAction(new RouteAction("show/add/files/to/group/{group_id}","show.add.group.files","showAddFilesToGroup","get",["add_groups_to_file"],[],"id"));
        $this->addAction(new RouteAction("add/files/to/group/{group_id}","add.group.files","addFilesToGroup","post",["add_groups_to_file"],[],"id"));
        $this->addAction(new RouteAction("remove/files/group/{group_id}","remove.group.files","removeFilesFromGroup","delete",["remove_files_from_group"],[],"id"));
    }
}

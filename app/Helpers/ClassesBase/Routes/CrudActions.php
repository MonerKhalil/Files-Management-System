<?php

namespace App\Helpers\ClassesBase\Routes;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesProcess\RolesPermissions\PermissionsMap;

abstract class CrudActions
{
    protected ?RouteAction $indexAction = null;
    protected ?RouteAction $indexTrashesAction = null;
    protected ?RouteAction $indexAllAction = null;
    protected ?RouteAction $showSearchFieldsAction = null;
    protected ?RouteAction $createAction = null;
    protected ?RouteAction $storeAction = null;
    protected ?RouteAction $showAction = null;
    protected ?RouteAction $editAction = null;
    protected ?RouteAction $updateAction = null;
    protected ?RouteAction $deleteAction = null;
    protected ?RouteAction $deleteMultiAction = null;
    protected ?RouteAction $forceDeleteAction = null;
    protected ?RouteAction $forceDeleteMultiAction = null;
    protected ?RouteAction $restoreAction = null;
    protected ?RouteAction $restoreMultiAction = null;
    protected ?RouteAction $restoreAllAction = null;
    protected ?RouteAction $clearTrashesAction = null;
    protected ?RouteAction $exportXLSXAction = null;
    protected ?RouteAction $exportPDFAction = null;

    private array $actions = [];

    private ?string $nameCrud = null;

    private array $middlewares = [];

    private ?array $finalData = null;

    public function __construct(private ?BaseRepository $repository = null)
    {
        $this->nameCrud = $this->repository->nameTable;
        $this->initActions();
    }

    protected abstract function handle():void;

    protected abstract function controller():string;

    private function initActions(){
        $this->indexAction = new RouteAction("","index","index","get",[$this->getPermissionWithNameCrud(PermissionsMap::READ),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->indexTrashesAction = new RouteAction("trashes","index.trashes","indexTrashes","get",[$this->getPermissionWithNameCrud(PermissionsMap::READ_TRASHES),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->indexAllAction = new RouteAction("all","get.all","indexAll","get",[$this->getPermissionWithNameCrud(PermissionsMap::READ),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->showSearchFieldsAction = new RouteAction("get/fields/show_search","get.fields.show_search","showSearchFields","get",[$this->getPermissionWithNameCrud(PermissionsMap::READ),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->createAction = new RouteAction("create","create","create","get",[$this->getPermissionWithNameCrud(PermissionsMap::CREATE),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->storeAction = new RouteAction("create","store","store","post",[$this->getPermissionWithNameCrud(PermissionsMap::CREATE),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->showAction = new RouteAction("show/{id}","show","show","get",[$this->getPermissionWithNameCrud(PermissionsMap::READ),$this->getPermissionWithNameCrud(PermissionsMap::All)],[],"id");
        $this->editAction = new RouteAction("edit/{id}","edit","edit","get",[$this->getPermissionWithNameCrud(PermissionsMap::UPDATE),$this->getPermissionWithNameCrud(PermissionsMap::All)],[],"id");
        $this->updateAction = new RouteAction("edit/{id}","update","update","put",[$this->getPermissionWithNameCrud(PermissionsMap::UPDATE),$this->getPermissionWithNameCrud(PermissionsMap::All)],[],"id");
        $this->deleteAction = new RouteAction("delete/{id}","delete","delete","delete",[$this->getPermissionWithNameCrud(PermissionsMap::DELETE),$this->getPermissionWithNameCrud(PermissionsMap::All)],[],"id");
        $this->deleteMultiAction = new RouteAction("delete/records","delete.records","multiDelete","delete",[$this->getPermissionWithNameCrud(PermissionsMap::DELETE),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->forceDeleteAction = new RouteAction("force/delete/{id}","force.delete","forceDelete","delete",[$this->getPermissionWithNameCrud(PermissionsMap::FORCE_DELETE),$this->getPermissionWithNameCrud(PermissionsMap::All)],[],"id");
        $this->forceDeleteMultiAction = new RouteAction("force/delete/records","force.delete.records","multiForceDelete","delete",[$this->getPermissionWithNameCrud(PermissionsMap::FORCE_DELETE),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->restoreAction = new RouteAction("restore/{id}","restore","restore","post",[$this->getPermissionWithNameCrud(PermissionsMap::RESTORE),$this->getPermissionWithNameCrud(PermissionsMap::All)],[],"id");
        $this->restoreMultiAction = new RouteAction("restore/records","restore.records","multiRestore","post",[$this->getPermissionWithNameCrud(PermissionsMap::RESTORE),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->restoreAllAction = new RouteAction("restore/all/records","restore.all.records","restoreAll","post",[$this->getPermissionWithNameCrud(PermissionsMap::RESTORE),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->clearTrashesAction = new RouteAction("clear/trashes","clear.trashes","clearTrashes","delete",[$this->getPermissionWithNameCrud(PermissionsMap::FORCE_DELETE),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->exportXLSXAction = new RouteAction("export/excel","export.excel","exportXLSX","get",[$this->getPermissionWithNameCrud(PermissionsMap::Export),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->exportPDFAction = new RouteAction("export/pdf","export.pdf","exportPDF","get",[$this->getPermissionWithNameCrud(PermissionsMap::Export),$this->getPermissionWithNameCrud(PermissionsMap::All)]);
        $this->handle();
        $this->actions = Arr::except(get_object_vars($this),["actions","nameCrud","middlewares","repository","finalData"]);
    }

    private function getPermissionWithNameCrud(string $permission): string
    {
        return "{$permission}_{$this->nameCrud}";
    }

    protected function getActions(){
        return $this->actions;
    }

    protected function addAction(RouteAction $action){
        $this->actions[$action->getAction() . "Action"] = $action;
        return $this;
    }

    protected function addMiddleware(string $middleware){
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function registerRoute(){
        if (is_null($this->finalData)){
            $this->finalData = [];
        }
        Route::prefix($this->nameCrud)
            ->name("{$this->nameCrud}.")
            ->controller($this->controller())
            ->middleware($this->middlewares)
            ->group(function (){
                foreach ($this->getActions() as $key => $action){
                    if (!$action->checkIsActive()){
                        continue;
                    }
                    Route::{$action->getMethod()}($action->getUrl(),$action->getAction())
                        ->name($action->getName())
                        ->middleware($action->getMiddlewares())
                        ->middleware(["permissions:".implode("|",$action->getPermissions())]);
                    #...Todo...
                    #set Data..
                    $this->finalData[$key] = [
                        "route_name" => "{$this->nameCrud}.{$action->getName()}",
                        "method" => $action->getMethod(),
                        "permissions" => $action->getPermissions(),
                        "keySendInUrl" => $action->getKeySendInUrl(),
                    ];
                }
            });
    }

    public function toArray(): ?array
    {
        if (is_null($this->finalData)){
            $data = [];
            foreach ($this->getActions() as $action => $obj){
                if (!$obj->checkIsActive()){
                    continue;
                }
                $data[$action] = [
                    "route_name" => "{$this->nameCrud}.{$obj->getName()}",
                    "method" => $obj->getMethod(),
                    "permissions" => $obj->getPermissions(),
                    "keySendInUrl" => $obj->getKeySendInUrl(),
                ];
            }
            return $data;
        }
        return $this->finalData;
    }
}

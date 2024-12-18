<?php

namespace App\Http\Controllers\CrudControllers;

use App\Helpers\MyApp;
use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Http\Request;
use App\Http\Requests\CrudRequests\RoleRequest;
use App\Http\CrudFiles\Repositories\Interfaces\IRoleRepository;

class RoleController extends Controller
{
    private $viewFieldsCrud = null;

    private $routesAction = null;

    public function __construct(public IRoleRepository $IRoleRepository){
        $this->viewFieldsCrud = $this->IRoleRepository->viewFields();
        $this->routesAction = $this->IRoleRepository->actions()->toArray();
    }

    /**
     * @description get data with pagination,filter and without deleted.
     * @return mixed
     * @author moner khalil
     */
    public function index() {
        ${$this->IRoleRepository->nameTable} = $this->IRoleRepository->get(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->IRoleRepository->nameTable} = $this->IRoleRepository->getOnlyTrashes(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->IRoleRepository->nameTable} = MyApp::Classes()->cacheProcess->getAllRoles();
        return $this->responseSuccess(get_defined_vars());
    }

    public function showSearchFields(){
        $fieldsShow = $this->viewFieldsCrud->getFieldsShow();
        $fieldsSearch = $this->viewFieldsCrud->getFieldsSearch();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(get_defined_vars());
    }

    public function create(RoleService $roleService) {
        $fieldsShow = $this->viewFieldsCrud->getFieldsShow();
        $routesActions = $this->routesAction;
        $permissions = $roleService->getPermissions();
        return $this->responseSuccess(compact("fieldsShow","routesActions","permissions"));
    }

    public function store(RoleRequest $request,RoleService $roleService) {
        $data = $roleService->createOrUpdate($request->validated(),$request->permissions ?? []);
        return $this->responseSuccess($data);
    }

    public function show($id) {
        $item = $this->IRoleRepository->find($id,"id",function ($q){
            return $q->with(["permissions"]);
        },true);
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id,RoleService $roleService) {
        $role = $this->IRoleRepository->find($id,"id",null,true);
        $rolePermissions = $role->permissions()->pluck('name', 'id')->toArray();
        $permissions = $roleService->getPermissions();
        $fieldsShow = $this->viewFieldsCrud->getFieldsShow();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(compact("role","rolePermissions","permissions","routesActions","fieldsShow"));
    }

    public function update(RoleRequest $request, $id,RoleService $roleService) {
        $data = $roleService->createOrUpdate($request->validated(),$request->permissions ?? [],$id);
        return $this->responseSuccess($data);
    }

    public function delete($id) {
        $this->IRoleRepository->delete($id);
        return $this->responseSuccess();
    }

    public function multiDelete(Request $request) {
        $values = $this->requestValidateIdsDelete($request, $this->IRoleRepository->nameTable);
        $this->IRoleRepository->multiDelete($values);
        return $this->responseSuccess();
    }

    public function forceDelete($id) {
        $this->IRoleRepository->forceDelete($id);
        return $this->responseSuccess();
    }

    public function multiForceDelete(Request $request) {
        $values = $this->requestValidateIds($request, $this->IRoleRepository->nameTable);
        $this->IRoleRepository->multiForceDelete($values);
        return $this->responseSuccess();
    }

    public function restore($id) {
        $this->IRoleRepository->restore($id);
        return $this->responseSuccess();
    }

    public function multiRestore(Request $request) {
        $values = $this->requestValidateIdsRestore($request, $this->IRoleRepository->nameTable);
        $this->IRoleRepository->multiRestore($values);
        return $this->responseSuccess();
    }

    public function restoreAll() {
        $this->IRoleRepository->restoreAll();
        return $this->responseSuccess();
    }

    public function clearTrashed() {
        $this->IRoleRepository->clearTrashes();
        return $this->responseSuccess();
    }

    public function exportXLSX(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IRoleRepository->exportXLSX($isEmpty,$ids);
    }

    public function exportPDF(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IRoleRepository->exportPDF($ids);
    }
}

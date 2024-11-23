<?php

namespace App\Http\Controllers\CrudControllers;

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
        ${$this->IRoleRepository->nameTable} = $this->IRoleRepository->get();
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->IRoleRepository->nameTable} = $this->IRoleRepository->getOnlyTrashes();
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->IRoleRepository->nameTable} = $this->IRoleRepository->get(true,false,null,false);
        return $this->responseSuccess(get_defined_vars());
    }

    public function showSearchFields(){
        $fieldsShow = $this->viewFieldsCrud->getFieldsShow();
        $fieldsSearch = $this->viewFieldsCrud->getFieldsSearch();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(get_defined_vars());
    }

    public function create(RoleService $roleService) {
        $routesActions = $this->routesAction;
        $permissions = $roleService->getPermissions();
        return $this->responseSuccess(compact("routesActions","permissions"));
    }

    public function store(RoleRequest $request) {
        $item = $this->IRoleRepository->create($request->validated());
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id,RoleService $roleService) {
        $role = $this->IRoleRepository->find($id);
        $rolePermissions = $role->permissions()->pluck('name', 'id')->toArray();
        $routesActions = $this->routesAction;
        $permissions = $roleService->getPermissions();
        return $this->responseSuccess(compact("role","rolePermissions","permissions","routesActions"));
    }

    public function update(RoleRequest $request, $id) {
        $item = $this->IRoleRepository->update($request->validated(),$id);
        return $this->responseSuccess(compact("item"));
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

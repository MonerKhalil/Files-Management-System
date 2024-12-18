<?php

namespace App\Http\Controllers\CrudControllers;

use App\DTO\UserDTO;
use App\Exceptions\CrudException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CrudRequests\UserRequest;
use App\Http\CrudFiles\Repositories\Interfaces\IUserRepository;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $viewFieldsCrud = null;

    private $routesAction = null;

    public function __construct(public IUserRepository $IUserRepository){
        $this->viewFieldsCrud = $this->IUserRepository->viewFields();
        $this->routesAction = $this->IUserRepository->actions()->toArray();
    }

    /**
     * @description get data with pagination,filter and without deleted.
     * @return mixed
     * @author moner khalil
     */
    public function index() {
        ${$this->IUserRepository->nameTable} = $this->IUserRepository->get(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->IUserRepository->nameTable} = $this->IUserRepository->getOnlyTrashes(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->IUserRepository->nameTable} = $this->IUserRepository->get(true,false,null,false);
        return $this->responseSuccess(get_defined_vars());
    }

    public function showSearchFields(){
        $fieldsShow = $this->viewFieldsCrud->getFieldsShow();
        $fieldsSearch = $this->viewFieldsCrud->getFieldsSearch();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(get_defined_vars());
    }

    public function create() {
        $fieldsCreate = $this->viewFieldsCrud->getFieldsCreate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(get_defined_vars());
    }

    public function store(UserRequest $request) {
        try {
            DB::beginTransaction();
            $item = $this->IUserRepository->create($request->validated());
            $item->addRole($item->role_id);
            DB::commit();
            return $this->responseSuccess(compact("item"));
        }catch (\Exception $e){
            DB::rollBack();
            throw new CrudException($e->getMessage());
        }
    }

    public function show($id) {
        $item = $this->IUserRepository->find($id,"id",null,true);
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id) {
        $item = $this->IUserRepository->find($id,"id",null,true);
        $fieldsUpdate = $this->viewFieldsCrud->getFieldsUpdate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(compact("item","fieldsUpdate","routesActions"));
    }

    public function update(UserRequest $request, $id) {
        try {
            $item = $this->IUserRepository->update($request->validated(),$id);
            $item->syncRoles([$item->role_id]);
            return $this->responseSuccess(compact("item"));
        }catch (\Exception $e){
            DB::rollBack();
            throw new CrudException($e->getMessage());
        }
    }

    public function delete($id) {
        $this->IUserRepository->delete($id);
        return $this->responseSuccess();
    }

    public function multiDelete(Request $request) {
        $values = $this->requestValidateIdsDelete($request, $this->IUserRepository->nameTable);
        $this->IUserRepository->multiDelete($values);
        return $this->responseSuccess();
    }

    public function forceDelete($id) {
        $this->IUserRepository->forceDelete($id);
        return $this->responseSuccess();
    }

    public function multiForceDelete(Request $request) {
        $values = $this->requestValidateIds($request, $this->IUserRepository->nameTable);
        $this->IUserRepository->multiForceDelete($values);
        return $this->responseSuccess();
    }

    public function restore($id) {
        $this->IUserRepository->restore($id);
        return $this->responseSuccess();
    }

    public function multiRestore(Request $request) {
        $values = $this->requestValidateIdsRestore($request, $this->IUserRepository->nameTable);
        $this->IUserRepository->multiRestore($values);
        return $this->responseSuccess();
    }

    public function restoreAll() {
        $this->IUserRepository->restoreAll();
        return $this->responseSuccess();
    }

    public function clearTrashed() {
        $this->IUserRepository->clearTrashes();
        return $this->responseSuccess();
    }

    public function exportXLSX(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IUserRepository->exportXLSX($isEmpty,$ids);
    }

    public function exportPDF(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IUserRepository->exportPDF($ids);
    }
}

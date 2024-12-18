<?php

namespace App\Http\Controllers\CrudControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CrudRequests\GroupManagerRequest;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;

class GroupManagerController extends Controller
{
    private $viewFieldsCrud = null;

    private $routesAction = null;

    public function __construct(public IGroupManagerRepository $IGroupManagerRepository){
        $this->viewFieldsCrud = $this->IGroupManagerRepository->viewFields();
        $this->routesAction = $this->IGroupManagerRepository->actions()->toArray();
    }

    /**
     * @description get data with pagination,filter and without deleted.
     * @return mixed
     * @author moner khalil
     */
    public function index() {
        ${$this->IGroupManagerRepository->nameTable} = $this->IGroupManagerRepository->get(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->IGroupManagerRepository->nameTable} = $this->IGroupManagerRepository->getOnlyTrashes(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->IGroupManagerRepository->nameTable} = $this->IGroupManagerRepository->get(true,false,null,false);
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

    public function store(GroupManagerRequest $request) {
        $item = $this->IGroupManagerRepository->create($request->validated());
        return $this->responseSuccess(compact("item"));
    }

    public function show($id) {
        $item = $this->IGroupManagerRepository->find($id,"id",null,true);
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id,$group = null) {
        $item = is_null($group) ? $this->IGroupManagerRepository->find($id,"id",null,true) : $group;
        $fieldsUpdate = $this->viewFieldsCrud->getFieldsUpdate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(compact("item","fieldsUpdate","routesActions"));
    }

    public function update(GroupManagerRequest $request, $id) {
        $item = $this->IGroupManagerRepository->update($request->validated(),$id);
        return $this->responseSuccess(compact("item"));
    }

    public function delete($id) {
        $this->IGroupManagerRepository->delete($id);
        return $this->responseSuccess();
    }

    public function multiDelete(Request $request) {
        $values = $this->requestValidateIdsDelete($request, $this->IGroupManagerRepository->nameTable);
        $this->IGroupManagerRepository->multiDelete($values);
        return $this->responseSuccess();
    }

    public function forceDelete($id) {
        $this->IGroupManagerRepository->forceDelete($id);
        return $this->responseSuccess();
    }

    public function multiForceDelete(Request $request) {
        $values = $this->requestValidateIds($request, $this->IGroupManagerRepository->nameTable);
        $this->IGroupManagerRepository->multiForceDelete($values);
        return $this->responseSuccess();
    }

    public function restore($id) {
        $this->IGroupManagerRepository->restore($id);
        return $this->responseSuccess();
    }

    public function multiRestore(Request $request) {
        $values = $this->requestValidateIdsRestore($request, $this->IGroupManagerRepository->nameTable);
        $this->IGroupManagerRepository->multiRestore($values);
        return $this->responseSuccess();
    }

    public function restoreAll() {
        $this->IGroupManagerRepository->restoreAll();
        return $this->responseSuccess();
    }

    public function clearTrashed() {
        $this->IGroupManagerRepository->clearTrashes();
        return $this->responseSuccess();
    }

    public function exportXLSX(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IGroupManagerRepository->exportXLSX($isEmpty,$ids);
    }

    public function exportPDF(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IGroupManagerRepository->exportPDF($ids);
    }
}

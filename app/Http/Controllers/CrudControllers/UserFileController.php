<?php

namespace App\Http\Controllers\CrudControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CrudRequests\UserFileRequest;
use App\Http\CrudFiles\Repositories\Interfaces\IUserFileRepository;

class UserFileController extends Controller
{
    private $viewFieldsCrud = null;

    private $routesAction = null;

    public function __construct(public IUserFileRepository $IUserFileRepository){
        $this->viewFieldsCrud = $this->IUserFileRepository->viewFields();
        $this->routesAction = $this->IUserFileRepository->actions()->toArray();
    }

    /**
     * @description get data with pagination,filter and without deleted.
     * @return mixed
     * @author moner khalil
     */
    public function index() {
        ${$this->IUserFileRepository->nameTable} = $this->IUserFileRepository->get(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->IUserFileRepository->nameTable} = $this->IUserFileRepository->getOnlyTrashes(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->IUserFileRepository->nameTable} = $this->IUserFileRepository->get(true,false,null,false);
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

    public function store(UserFileRequest $request) {
        $item = $this->IUserFileRepository->create($request->validated());
        return $this->responseSuccess(compact("item"));
    }

    public function show($id) {
        $item = $this->IUserFileRepository->find($id,"id",null,true);
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id) {
        $item = $this->IUserFileRepository->find($id,"id",null,true);
        $fieldsUpdate = $this->viewFieldsCrud->getFieldsUpdate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(compact("item","fieldsUpdate","routesActions"));
    }

    public function update(UserFileRequest $request, $id) {
        $item = $this->IUserFileRepository->update($request->validated(),$id);
        return $this->responseSuccess(compact("item"));
    }

    public function delete($id) {
        $this->IUserFileRepository->delete($id);
        return $this->responseSuccess();
    }

    public function multiDelete(Request $request) {
        $values = $this->requestValidateIdsDelete($request, $this->IUserFileRepository->nameTable);
        $this->IUserFileRepository->multiDelete($values);
        return $this->responseSuccess();
    }

    public function forceDelete($id) {
        $this->IUserFileRepository->forceDelete($id);
        return $this->responseSuccess();
    }

    public function multiForceDelete(Request $request) {
        $values = $this->requestValidateIds($request, $this->IUserFileRepository->nameTable);
        $this->IUserFileRepository->multiForceDelete($values);
        return $this->responseSuccess();
    }

    public function restore($id) {
        $this->IUserFileRepository->restore($id);
        return $this->responseSuccess();
    }

    public function multiRestore(Request $request) {
        $values = $this->requestValidateIdsRestore($request, $this->IUserFileRepository->nameTable);
        $this->IUserFileRepository->multiRestore($values);
        return $this->responseSuccess();
    }

    public function restoreAll() {
        $this->IUserFileRepository->restoreAll();
        return $this->responseSuccess();
    }

    public function clearTrashed() {
        $this->IUserFileRepository->clearTrashes();
        return $this->responseSuccess();
    }

    public function exportXLSX(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IUserFileRepository->exportXLSX($isEmpty,$ids);
    }

    public function exportPDF(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IUserFileRepository->exportPDF($ids);
    }
}

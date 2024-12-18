<?php

namespace App\Http\Controllers\CrudControllers;

use App\DTO\FileManagerDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CrudRequests\FileManagerRequest;
use App\Http\CrudFiles\Repositories\Interfaces\IFileManagerRepository;

class FileManagerController extends Controller
{
    private $viewFieldsCrud = null;

    private $routesAction = null;

    public function __construct(public IFileManagerRepository $IFileManagerRepository){
        $this->viewFieldsCrud = $this->IFileManagerRepository->viewFields();
        $this->routesAction = $this->IFileManagerRepository->actions()->toArray();
    }

    /**
     * @description get data with pagination,filter and without deleted.
     * @return mixed
     * @author moner khalil
     */
    public function index() {
        ${$this->IFileManagerRepository->nameTable} = $this->IFileManagerRepository->get(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->IFileManagerRepository->nameTable} = $this->IFileManagerRepository->getOnlyTrashes(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->IFileManagerRepository->nameTable} = $this->IFileManagerRepository->get(true,false);
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

    public function store(FileManagerRequest $request) {
        $item = $this->IFileManagerRepository->create($request->validated());
        return $this->responseSuccess(compact("item"));
    }

    public function show($id) {
        $item = $this->IFileManagerRepository->find($id,"id",null,true);
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id) {
        $item = $this->IFileManagerRepository->find($id,"id",null,true);
        $fieldsUpdate = $this->viewFieldsCrud->getFieldsUpdate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(compact("item","fieldsUpdate","routesActions"));
    }

    public function update(FileManagerRequest $request, $id) {
        $item = $this->IFileManagerRepository->update($request->validated(),$id);
        return $this->responseSuccess(compact("item"));
    }

    public function delete($id) {
        $this->IFileManagerRepository->delete($id);
        return $this->responseSuccess();
    }

    public function multiDelete(Request $request) {
        $values = $this->requestValidateIdsDelete($request, $this->IFileManagerRepository->nameTable);
        $this->IFileManagerRepository->multiDelete($values);
        return $this->responseSuccess();
    }

    public function forceDelete($id) {
        $this->IFileManagerRepository->forceDelete($id);
        return $this->responseSuccess();
    }

    public function multiForceDelete(Request $request) {
        $values = $this->requestValidateIds($request, $this->IFileManagerRepository->nameTable);
        $this->IFileManagerRepository->multiForceDelete($values);
        return $this->responseSuccess();
    }

    public function restore($id) {
        $this->IFileManagerRepository->restore($id);
        return $this->responseSuccess();
    }

    public function multiRestore(Request $request) {
        $values = $this->requestValidateIdsRestore($request, $this->IFileManagerRepository->nameTable);
        $this->IFileManagerRepository->multiRestore($values);
        return $this->responseSuccess();
    }

    public function restoreAll() {
        $this->IFileManagerRepository->restoreAll();
        return $this->responseSuccess();
    }

    public function clearTrashed() {
        $this->IFileManagerRepository->clearTrashes();
        return $this->responseSuccess();
    }

    public function exportXLSX(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IFileManagerRepository->exportXLSX($isEmpty,$ids);
    }

    public function exportPDF(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IFileManagerRepository->exportPDF($ids);
    }
}

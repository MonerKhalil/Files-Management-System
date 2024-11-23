<?php

namespace App\Http\Controllers;

use App\Helpers\Traits\TResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\Rule;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, TResponse;

    protected function requestValidateIds($request ,$nameTable ,$nameInput = "ids" ,$key = "id"){
        $request->validate([
            "$nameInput" => ["required","array"],
            "$nameInput.*" => ["required","numeric","distinct",Rule::exists($nameTable,$key)],
        ]);
        return $request->{$nameInput};
    }

    protected function requestValidateIdsDelete($request ,$nameTable ,$nameInput = "ids" ,$key = "id"){
        $request->validate([
            "$nameInput" => ["required","array"],
            "$nameInput.*" => ["required","numeric","distinct",Rule::exists($nameTable,$key)->whereNull("deleted_at")],
        ]);
        return $request->{$nameInput};
    }

    protected function requestValidateIdsRestore($request ,$nameTable ,$nameInput = "ids" ,$key = "id"){
        $request->validate([
            "$nameInput" => ["required","array"],
            "$nameInput.*" => ["required","numeric","distinct",Rule::exists($nameTable,$key)->whereNotNull("deleted_at")],
        ]);
        return $request->{$nameInput};
    }

    protected function getParametersInExportFunctions($request){
        $isEmpty = $request->__is_empty;
        $isEmpty = isset($isEmpty) && is_bool($isEmpty) && $isEmpty;
        $ids = $request->__ids;
        $idsCheck = isset($ids) && is_array($ids);
        $ids = $idsCheck ? $ids : null;
        return compact("isEmpty","ids");
    }
}

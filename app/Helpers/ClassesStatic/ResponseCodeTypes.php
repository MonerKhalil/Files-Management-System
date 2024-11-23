<?php

namespace App\Helpers\ClassesStatic;

class ResponseCodeTypes
{
    #Codes-Status-Suc
    const CODE_SUC_REQUEST = 200;

    #Codes-Status-Err
    const CODE_ERROR_BAD_REQUEST = 400;
    const CODE_ERROR_NOT_LOGIN = 401;
    const CODE_ERROR_NOT_ACCESS = 403;
    const CODE_ERROR_NOT_FOUND = 404;
    const CODE_ERROR_Method_Not_Allowed = 405;
    const CODE_ERROR_VALIDATION  = 422;
    const CODE_ERROR_Internal_Server  = 500;

    #Pages-Errors
    const Page_404 = "pages.errors.404" ;
    const Page_500 = "pages.errors.500" ;

    public static function getMessageCodeError(int $code){
//        $errors = [
//
//        ];
//        if (is){
//
//        }
    }
}

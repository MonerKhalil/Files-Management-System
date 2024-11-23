<?php

namespace App\Helpers\ClassesProcess;

use App\Helpers\ClassesBase\BaseRequest;
use App\Helpers\ClassesStatic\JsonHandle;
use App\Helpers\ClassesStatic\MessagesFlash;
use App\Helpers\ClassesStatic\ResponseCodeTypes;
use App\Helpers\MyApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ResponseProcess
{
    public function setMessageSuccess(string $message = null, string $typeProcess = null){
        MessagesFlash::setMsgSuccess($message,$typeProcess);
        return $this;
    }

    /**
     * @param mixed|null $dataResponse
     * @param string|null $viewName
     * @param string|null $routeName
     * @param array $parametersRouteName
     * @param bool $isBack
     * @param string|null $urlTo
     * @return JsonResponse|RedirectResponse|Response|null
     */
    public function responseSuccess(mixed $dataResponse = null, string $viewName = null,
                                    string $routeName = null, array $parametersRouteName = [],
                                    bool $isBack = false, string $urlTo = null
    ): Response|JsonResponse|RedirectResponse|null
    {
        if (BaseRequest::urlIsApi() || (is_null($viewName) && is_null($routeName) && is_null($urlTo) && !$isBack)){
            return JsonHandle::dataHandle($dataResponse);
        }
        if (!is_null($viewName)){
            return response()->view($viewName,$dataResponse??[]);
        }
        if ($isBack){
            return redirect()->back();
        }
        if (!is_null($routeName)){
            return redirect()->route($routeName,$parametersRouteName);
        }
        if (!is_null($urlTo)){
            return redirect()->to($urlTo);
        }
        return null;
    }

    /**
     * @param $error
     * @param $exception
     * @param int $code
     * @param bool $isBack
     * @param string|null $ViewName
     * @param array $dataView
     * @param string|null $RouteName
     * @param array $dataRoute
     * @return JsonResponse|RedirectResponse|Response|null
     */
    public function responseError($error, $exception, int $code = ResponseCodeTypes::CODE_ERROR_BAD_REQUEST, bool $isBack = false
        , string $ViewName = null, array $dataView = [], string $RouteName = null, array $dataRoute = []): Response|JsonResponse|RedirectResponse|null
    {
        MessagesFlash::setMsgError($error);
        if (BaseRequest::urlIsApi() || (is_null($ViewName) && is_null($RouteName) && !$isBack)){
            return JsonHandle::errorHandle($exception,$code);
        }
        if ($isBack){
            return redirect()->back();
        }
        if (!is_null($ViewName)){
            return response()->view($ViewName,$dataView);
        }
        if (!is_null($RouteName)){
            return redirect()->route($RouteName,$dataRoute);
        }
        return null;
    }
}

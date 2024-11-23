<?php

namespace App\Helpers\ClassesStatic;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class JsonHandle
{
    /**
     * @param mixed $data
     * @return JsonResponse
     * @author moner khalil
     */
    public static function dataHandle(mixed $data): JsonResponse
    {
        $finalDate = [
            'timestamp' => now(),
            'status' => true,
            'code' => ResponseCodeTypes::CODE_SUC_REQUEST,
        ];
        $finalDate['message'] = MessagesFlash::getMsgSuccess() ?? MessagesFlash::msgForceSuccess();
        $finalDate['data'] = $data;
        self::clearMessages(false);
        return response()->json($finalDate,$finalDate['code']);
    }

    /**
     * @param string $exception
     * @param int $code
     * @return JsonResponse
     * @author moner khalil
     */
    public static function errorHandle(string $exception, int $code = ResponseCodeTypes::CODE_ERROR_BAD_REQUEST): JsonResponse
    {
        $finalError = [
            'timestamp' => now(),
            'status' => false,
            'code' => $code,
            'exception' => $exception,
        ];
        $error = MessagesFlash::getMsgError() ?? MessagesFlash::msgForceError();
        if (is_array($error)){
            $finalError["errors"] = $error;
        }else{
            $finalError["errors"] = [
                "message" => $error,
            ];
        }
        self::clearMessages();
        return response()->json($finalError,$code);
    }

    /**
     * @param bool $isError
     */
    private static function clearMessages(bool $isError = true){
        if ($isError && !is_null(MessagesFlash::getMsgError())){
            Session::remove(MessagesFlash::error);
        }
        if (!$isError && !is_null(MessagesFlash::getMsgSuccess())){
            Session::remove(MessagesFlash::success);
        }
    }
}

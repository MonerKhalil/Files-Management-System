<?php

namespace App\Helpers\ClassesStatic;

use Illuminate\Support\Facades\Session;

class MessagesFlash
{
    public const success = "Success";
    public const error = "Error";

    /**
     * @return mixed
     */
    public static function getMsgSuccess(): mixed
    {
        return Session::has(self::success) ? Session::get(self::success) : null;
    }

    /**
     * @param string|null $message
     * @param string|null $typeProcess
     */
    public static function setMsgSuccess(string $message = null, string $typeProcess = null){
        $messageFinal = $message ?? self::Messages($typeProcess ?? "...");
        Session::flash(self::success,$messageFinal);
    }

    /**
     * @return mixed
     */
    public static function msgForceSuccess(): mixed
    {
        self::setMsgSuccess();
        return self::getMsgSuccess();
    }

    /**
     * @return mixed
     */
    public static function getMsgError(): mixed
    {
        return Session::has(self::error) ? Session::get(self::error) : null;
    }

    /**
     * @param mixed $error
     */
    public static function setMsgError(mixed $error = null){
        $errorFinal = $error ?? __("errors.err_default");
        Session::flash(self::error,$errorFinal);
    }

    /**
     * @return mixed
     */
    public static function msgForceError(): mixed
    {
        self::setMsgError();
        return self::getMsgError();
    }

    /**
     * @param string $process
     * @return mixed
     * @author moner khalil
     */
    private static function Messages(string $process): mixed
    {
        $keyMessage = "messages";
        $msg = [
            "create" => __("$keyMessage.suc_create"),
            "update" => __("$keyMessage.suc_update"),
            "delete" => __("$keyMessage.suc_delete"),
            "default" => __("$keyMessage.suc_default"),
        ];
        return $msg[$process] ?? $msg['default'];
    }
}

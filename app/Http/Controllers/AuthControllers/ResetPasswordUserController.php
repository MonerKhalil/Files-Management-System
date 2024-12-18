<?php

namespace App\Http\Controllers\AuthControllers;

use App\Helpers\ClassesBase\BaseRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\ResetPasswordRequest;
use App\Services\UserResetPasswordService;

class ResetPasswordUserController extends Controller
{
    public function __construct(private UserResetPasswordService $userService)
    {
    }

    public function forgetPassword(BaseRequest $request){
        $request->validate([
            "email" => ["required","email",$request->existsRow("users","email")],
        ]);
        $this->userService->sendForgetPasswordEmail($request->email);
        return $this->setMessageSuccess(__("auth.send_code_reset_password"))->responseSuccess();
    }

    public function resetPassword(ResetPasswordRequest $request){
        $email = $request->email;
        $password = $request->password;
        $token = $request->token;
        $this->userService->resetPassword($email,$password,$token);
        return $this->setMessageSuccess(__("auth.password_changed"))->responseSuccess();
    }
}

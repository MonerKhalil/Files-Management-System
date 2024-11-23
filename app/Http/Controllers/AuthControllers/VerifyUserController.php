<?php

namespace App\Http\Controllers\AuthControllers;

use App\Helpers\MyApp;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\VerifyEmailCodeRequest;
use App\Services\UserVerifyService;

class VerifyUserController extends Controller
{
    public function __construct(private UserVerifyService $userService)
    {
    }

    public function sendCodeVerifyEmail(){
        $user = MyApp::Classes()->user->get();
        $this->userService->sendVerifyEmail($user);
        return $this->setMessageSuccess(__("auth.send_code_verify"))->responseSuccess();
    }

    public function verifyEmailUser(VerifyEmailCodeRequest $request){
        $user = MyApp::Classes()->user->get();
        $this->userService->checkCodeVerifySucceed($user,$request->code);
        return $this->setMessageSuccess(__("auth.user_verified_suc"))->responseSuccess();
    }
}

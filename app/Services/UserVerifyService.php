<?php

namespace App\Services;

use App\Exceptions\MainException;
use App\Helpers\MyApp;
use App\Mail\VerifyUserMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserVerifyService
{
    public function sendVerifyEmail($user){
        $token = uniqid();
        User::where('id', $user->id)->update([
            "email_verify_token"=>MyApp::Classes()->stringProcess->strEncrypt($token),
            "email_verify_token_expired_at" => now()->addMinutes(10),
        ]);
        MyApp::Classes()->mailProcess->SendMail($user->email,new VerifyUserMail($token));
    }

    public function checkCodeVerifySucceed($user,$code){
        if (strtotime($user->email_verify_token_expired_at) < strtotime(now())){
            throw new MainException(__("errors.token_expired!"));
        }
        if (MyApp::Classes()->stringProcess->strDecrypt($user->email_verify_token) != $code){
            throw new MainException(__("errors.Invalid_Code!"));
        }
        User::where('id', $user->id)->update([
            "email_verify_token" => null,
            "email_verify_token_expired_at" => null,
            "email_verified_at" => now(),
        ]);
    }
}

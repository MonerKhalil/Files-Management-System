<?php

namespace App\Services;

use App\Exceptions\MainException;
use App\Helpers\MyApp;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserResetPasswordService
{
    public function sendForgetPasswordEmail($email){
        try {
            DB::beginTransaction();
            $token = uniqid();
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => MyApp::Classes()->stringProcess->strEncrypt($token),
                'created_at' => Carbon::now()
            ]);
            MyApp::Classes()->mailProcess->SendMail($email,new ResetPasswordMail($token),true);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    public function resetPassword($email,$password,$token){
        try {
            DB::beginTransaction();
            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' => $email,
                    'token' => $token
                ])->first();
            if (is_null($updatePassword)){
                throw new \Exception();
            }
            if (MyApp::Classes()->stringProcess->strDecrypt($updatePassword->token) != $token){
                throw new \Exception();
            }

            User::where('email', $email)->update(['password' => Hash::make($password)]);
            DB::table('password_resets')->where(['email'=> $email])->delete();
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException(__("errors.Invalid_Code!"));
        }
    }
}

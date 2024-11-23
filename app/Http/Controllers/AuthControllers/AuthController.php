<?php

namespace App\Http\Controllers\AuthControllers;

use App\DTO\UserDTO;
use App\Helpers\MyApp;
use App\Http\Controllers\Controller;
use App\Http\CrudFiles\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Services\UserVerifyService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private IUserRepository $IUserRepository)
    {
    }

    public function getUserAuth(){
        $userProcess = MyApp::Classes()->user;
        $user = $userProcess->get();
        $permissions = $userProcess->getPermissions();
        $roles = $userProcess->getRoles();
        return $this->responseSuccess(compact("user","permissions","roles"));
    }

    /**
     * @param RegisterRequest $request
     * @param UserVerifyService $userService
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|null
     */
    public function registerUser(RegisterRequest $request, UserVerifyService $userService){
        $user = $this->IUserRepository->create((new UserDTO($request->validated()))->toArray(),false);
        $userService->sendVerifyEmail($user);
        $token = $user->createToken($user->email)->accessToken;
        return $this->setMessageSuccess(__("auth.register_user"))->responseSuccess(compact("user","token"));
    }

    public function login(LoginRequest $request){
        $user = $this->IUserRepository->find($request->email,"email",null,false,false);
        if (!is_null($user) && Hash::check($request->password, $user->password)){
            $token = $user->createToken($user->email)->accessToken;
            return $this->setMessageSuccess(__("auth.login_user"))->responseSuccess(compact("user","token"));
        }
        throw ValidationException::withMessages([
            'email/password' => __('auth.failed'),
        ]);
    }

    public function logout(){
        $user = MyApp::Classes()->user->get();
        $user->tokens()->delete();
        return $this->setMessageSuccess(__("auth.logout_user"))->responseSuccess(null,null,"logout_user");
    }
}

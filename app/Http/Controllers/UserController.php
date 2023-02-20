<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UpdateEmailUserService;
use App\Services\UserService;
use App\Services\ResetPasswordService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    protected $userService, $resetPasswordService, $updateEmailUserService;

    public function __construct(
        UserService $userService,
        ResetPasswordService $resetPasswordService,
        UpdateEmailUserService $updateEmailUserService
    ) {
        $this->userService = $userService;
        $this->resetPasswordService = $resetPasswordService;
        $this->updateEmailUserService = $updateEmailUserService;
    }

    public function store(RegisterRequest $request)
    {
        $user = $this->userService->createUser([
            'email' => $request->email,
            'password' => $request->password
        ]);
        $token = $user->createToken('Token Name')->accessToken;
        return response()->json(['token' => $token], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Login failed'], 401);
        }
        $user = $request->user();
        $token = $user->createToken('Token Name')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $email = $this->updateEmailUserService->updateUserEmail($request->email);
        return $email;
    }
}


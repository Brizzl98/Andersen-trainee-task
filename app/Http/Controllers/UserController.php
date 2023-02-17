<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use App\Services\ResetPasswordService;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    protected $userService,$resetPasswordService;

    public function __construct(UserService $userService, ResetPasswordService $resetPasswordService){
        $this->userService = $userService;
        $this->resetPasswordService = $resetPasswordService;
    }
    public function store(RegisterRequest $request){
        $user = $this->userService->createUser([
            'email' => $request->email,
            'password' => $request->password
        ]);
        $token = $user->createToken('Token Name')->accessToken;
        return response()->json(['token' => $token], 201);
    }

    public function login(LoginRequest $request){
        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Login failed'], 401);
        }
        $user = $request->user();
        $token = $user->createToken('Token Name')->accessToken;

        return response()->json(['token' => $token], 200);
    }

}


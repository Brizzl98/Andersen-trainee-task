<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Services\ResetPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    protected $userService, $resetPasswordService;

    public function __construct(
        UserService $userService,
        ResetPasswordService $resetPasswordService,
    ) {
        $this->userService = $userService;
        $this->resetPasswordService = $resetPasswordService;
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

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $result = $this->userService->updateUserEmail($request->email, $user);
        return response()->json(['message' => 'Your email adress successfully updated, new adress is: ' . $result->email],
            200);
    }

    public function getUsers()
    {
        $users_list = $this->userService->getUsers();
        // Convert array to list, for easy display
        $users_list = implode(', ', $users_list);
        return response()->json(['message' => 'Users: ' . $users_list], 200);
    }

    public function getUserData(Request $request, $id)
    {
        // Retrieve the authenticated user from the request
        $user = $request->user();
        $response = $this->userService->getUserData($user, $id);
        return $response;
    }
}


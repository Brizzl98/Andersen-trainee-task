<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUsersDataRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Services\ResetPasswordService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    const ACTIVE = 1;
    const INACTIVE = 2;
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

        return response()->json(['token' => $token]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $result = $this->userService->updateUserEmail($request->email, User::find($id));
        return response()->json($result);
    }

    public function getUsers(GetUsersDataRequest $request)
    {
        return response()->json($this->userService->getUsers());
    }

    public function getUserData(GetUsersDataRequest $request, $id)
    {
        return  response()->json(User::find($id));;
    }
    public function delete(DeleteUserRequest $request){
        return response()->json($this->userService->delete($request->user(), self::INACTIVE));
    }
}


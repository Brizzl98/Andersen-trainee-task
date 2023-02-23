<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Services\ResetPasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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

    public function update(UpdateUserRequest $request, $id)
    {
        $requestedUser = User::find($id);
        if ($requestedUser) {
            $this->authorize('update', $requestedUser);
            $result = $this->userService->updateUserEmail($request->email, $requestedUser);
            return response()->json(['message' => 'Your email adress successfully updated, new adress is: ' . $result],
                200);
        }
        return response()->json(['error' => 'User not found'], 404);
    }

    public function getUsers()
    {
        $usersEmails = $this->userService->getUsers();
        return response()->json(['Users: ' => $usersEmails], 200);
    }

    public function getUserData(Request $request, $id)
    {
        // Retrieve the authenticated user from the request
        $requestedUser = User::find($id);
        if ($requestedUser) {
            $this->authorize('view', $requestedUser);
            $response = response()->json(User::find($id));
            return $response;
        }
        return response()->json(['error' => 'User not found'], 404);
    }
}


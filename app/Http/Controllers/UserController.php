<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Services\UpdatePasswordService;
use App\Services\UserService;
use App\Services\ResetPasswordService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class UserController extends Controller
{
    protected $userService,$resetPasswordService;

    public function __construct(UserService $userService, ResetPasswordService $resetPasswordService, UpdatePasswordService $updatePasswordService){
        $this->userService = $userService;
        $this->resetPasswordService = $resetPasswordService;
        $this->updatePasswordService=$updatePasswordService;
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

    public function resetPassword(ResetPasswordRequest $request){
        // Generate a reset token
        $resetToken = Str::random(60);
        // Get the user
        $user = User::where('email', $request->email)->first();

        $pass_reset = $this->resetPasswordService->resetUserspassword([
            'user_id' => $user->id,
            'token' => $resetToken,
            'email' => $request->email
        ]);
        // Return a response
        return response()->json(['message' => 'An email has been sent to your email address with instructions to reset your password.']);
    }
    public function UpdatePassword(UpdatePasswordRequest $request){
        // Update the user's password using the reset password service
        $this->updatePasswordService->updatePassword([
            'token'=>$request->token,
            'password'=>$request->password
        ]);

        // Return a success response
        return response()->json(['message' => 'Password updated successfully']);
    }
}


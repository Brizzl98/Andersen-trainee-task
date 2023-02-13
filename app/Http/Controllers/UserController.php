<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\ResetPassword;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Authenticatable;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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

    public function resetPassword(ResetPasswordRequest $request)
    {
        $email = $request->input('email');
        $user_id = auth()->user()->id;


        // Generate reset token
        $resetToken = Str::random(60);

        // Store reset token in ResetPassword table
        ResetPassword::create([
            'email' => $email,
            'token' => $resetToken,
            'user_id' => $user_id,
        ]);

        // Send email to the user with reset token
        $mailData = [
            'resetToken' => $resetToken,
        ];

        Mail::to($email)->send(new ResetPasswordMail($mailData));

        return response()->json(['message' => 'Reset password email sent']);
    }
}


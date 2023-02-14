<?php

namespace App\Http\Controllers;
use App\Mail\ResetPasswordMailer;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\ResetPassword;
use App\Models\User;
use App\Services\UserService;
use App\Services\ResetPasswordService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    protected $userService,$resetPasswordService;

    public function __construct(UserService $userService, ResetPasswordService $resetPasswordService)
    {
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

    public function resetPassword(ResetPasswordRequest $request)
    {
        // Generate a reset token
        $resetToken = Str::random(60);

        // Get the user
        $user = User::where('email', $request->email)->first();

        $pass_reset = $this->resetPasswordService->resetUserspass([
            'user_id' => $user->id,
            'token' => $resetToken
        ]);

        // Send an email to the user with the reset token
        Mail::to($request->email)->send(new ResetPasswordMailer($pass_reset));

        // Return a response
        return response()->json(['message' => 'An email has been sent to your email address with instructions to reset your password.']);
    }
}


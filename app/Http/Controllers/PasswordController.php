<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use App\Services\ResetPasswordService;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    protected $resetPasswordService;

    public function __construct( ResetPasswordService $resetPasswordService){
        $this->resetPasswordService = $resetPasswordService;
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
        $this->resetPasswordService->updatePassword([
            'token'=>$request->token,
            'password'=>$request->password
        ]);

        // Return a success response
        return response()->json(['message' => 'Password updated successfully']);
    }
}

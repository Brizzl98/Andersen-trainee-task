<?php
namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMailer;
use App\Models\ResetPassword;


class ResetPasswordService
{
    public function resetUserspassword(array $data)
    {
        // Store the reset token in the database
        $reset = ResetPassword::create([
            'user_id' => $data["user_id"],
            'token' => $data["token"],
        ]);
        // Send an email to the user with the reset token
        Mail::to($data["email"])->send(new ResetPasswordMailer($reset));
        return $reset;
    }
    public function updatePassword(array $data)
    {
        // Get the reset password record associated with the token
        $reset = ResetPassword::where('token', $data['token'])->first();

        // If no reset password record is found, throw an exception
        if (!$reset) {
            return 'Invalid reset token';
        }

        // Check if the reset password token has expired (2 hours)
        if (Carbon::parse($reset->created_at)->addHours(2)->isPast()) {
            return 'Reset token has expired';
        }
        // Update the user's password
        $user = User::where('id', $reset->user_id)->first();
        $user->password = bcrypt($data['password']);
        $user->save();

        // Delete the reset password record
        $reset->delete();
    }
}

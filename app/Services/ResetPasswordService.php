<?php
namespace App\Services;

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
            'token' => $data["token"]
        ]);
        // Send an email to the user with the reset token
        Mail::to($data['email'])->send(new ResetPasswordMailer($reset));

        return $reset;
    }

}

<?php
namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMailer;
use App\Models\ResetPassword;

class UpdateEmailUserService
{
    public function updateUserEmail($email)
    {
        $user = Auth::user();
        if ($user->email === $email) {
            // No need to update the email if it's already the same
            return "New and current emails are the same. No need to update";
        }
        $user->email = $email;
        $user->save();
        return $user->email;
    }

}

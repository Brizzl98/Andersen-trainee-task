<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;


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

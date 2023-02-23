<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data)
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        return $user;
    }

    public function updateUserEmail($email, $user)
    {
        if ($user->email === $email) {
            // No need to update the email if it's already the same
            return "New and current emails are the same. No need to update";
        }
        $user->email = $email;
        $newEmail = $user->email;
        $user->save();
        return $newEmail;
    }

    public function getUsers()
    {
        // Get emails from users table
        $usersEmails = User::all()->pluck('email')->toArray();
        return $usersEmails;
    }

}

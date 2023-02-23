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
        $user->save();
        return $user;
    }

    public function getUsers()
    {
        // Get emails from users table
        $usersEmails = User::all()->pluck('email')->toArray();
        return $usersEmails;
    }

    public function getUserData($user, $id)
    {
        // Retrieve the requested user from the database
        $requestedUser = User::find($id);

        // Check if the requested user was found
        if ($requestedUser) {
            // If the authenticated user is the same as the requested user, return the user object
            if ($user->id === $requestedUser->id) {
                $user_Data = User::where('id', $user->id)->first();
                return response()->json(['Your data' => $user_Data]);
            } else {
                // If the authenticated user is not the same as the requested user, return a 403 Forbidden response
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }
        // If the requested user was not found, return a 404 Not Found response
        return response()->json(['error' => 'User not found'], 404);
    }
}

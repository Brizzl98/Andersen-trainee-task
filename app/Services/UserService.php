<?php

namespace App\Services;

use App\Mail\DeleteMailer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Mail;

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
        return $email;
    }

    public function getUsers()
    {
        // Get emails from users table
        return  User::all()->pluck('email')->toArray();;
    }

    public function delete($user, $status)
    {
        $user->status =$status;
        $user->save();
        $pdf = new Dompdf();
        $pdf->loadHTML("<h1>User successfully deleted</h1>");
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        Mail::to($user->email)->send(new DeleteMailer($pdf));
        return $pdf;
    }
}

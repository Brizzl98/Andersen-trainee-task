<?php
namespace App\Services;

use App\Models\ResetPassword;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService
{
    public function resetUserspass(array $data)
    {
        // Store the reset token in the database
        $reset = ResetPassword::create([
            'user_id' => $data["user_id"],
            'token' => $data["token"]
        ]);

        return $reset;
    }
}

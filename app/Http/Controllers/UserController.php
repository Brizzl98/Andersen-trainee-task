<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = new User();
        $user->email =$request->email;
        $user->password = Hash::make($request->password);
        $user->save();


        $token = $user->createToken('Token Name')->accessToken;

        return response()->json(['token' => $token], 201);
    }
}


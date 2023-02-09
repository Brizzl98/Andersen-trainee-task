<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(RegisterRequest $request)
    {

        $user = new User();
        $user->email =$request->email;
        $user->password = Hash::make($request->password);
        $user->save();


        $token = $user->createToken('Token Name')->accessToken;

        return response()->json(['token' => $token], 201);
    }
}


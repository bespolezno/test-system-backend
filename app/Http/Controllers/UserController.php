<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        if (Auth::attempt($request->only(['username', 'password']))) {
            return response([
                'token' => Auth::user()->generateToken()
            ]);
        }

        return response([
            'errors' => [
                'username' => 'Invalid credentials'
            ]
        ], 422);
    }

    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);
        return response([
            'token' => $user->generateToken()
        ], 201);
    }
}

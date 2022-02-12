<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthenticationController extends Controller
{   
    public function login(LoginRequest $request)
    {
        $fields = $request->validated();
        $user = User::where('email', $fields['email'])->first();
        // Check user and password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Wrong credentials'
            ], 401);
        }

        $token = $user->createToken('EventSearchToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
                'message'=> 'Logged Out'
            ],200);
    }

    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        
        $token = $user->createToken('EventSearchToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    
}

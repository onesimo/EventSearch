<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthLoginRegisterResource;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $fields = $request->validated();
        $user = User::where('email', $fields['email'])->first();
        // Check user and password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'Invalid Credentials'], response::HTTP_UNAUTHORIZED);
        }

        $user->token = $user->createToken('EventSearchToken')->plainTextToken;
        
        return new AuthLoginRegisterResource($user);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response(['message'=> 'Logged Out'], response::HTTP_OK);
    }

    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        
        $user->token = $user->createToken('EventSearchToken')->plainTextToken;

        return  new AuthLoginRegisterResource($user);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;

class AuthController extends Controller
{
    public function register (RegisterUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        event(new Registered($user));

        $access_token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration Success',
            'access_token' => $access_token,
            'user' => $user
        ], 201);
    }

    public function login (LoginUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if ( !$user ) {

            return response()->json([
                'message' => 'User Does Not Exists'
            ], 404);

        } else if ( !Hash::check($validated['password'], $user->password) ) {

            return response()->json([
                'message' => 'Password Does Not Match Credentials'
            ], 401);
        }

        $access_token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successful',
            'access_token' => $access_token,
            'user' => $user
        ], 200);
    }

    public function logout (Request $request)
    {
         // Delete token
         $request->user()->currentAccessToken()->delete();

         return response()->json([
            'message' => 'Logout Success'
         ], 201);
    }

}

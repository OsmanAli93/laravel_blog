<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
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
                'message' => 'Password Is Incorrect'
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

    public function forgotPassword (Request $request) {

        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                ? response()->json(['status' => $status], 200)
                : response()->json(['email' => $status], 400);
    }

    public function resetPassword (Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'Password has been reset successfully', 'status' => $status], 200)
                : response()->json(['message' => 'Password reset failed', 'status' => $status], 400);
    }

}

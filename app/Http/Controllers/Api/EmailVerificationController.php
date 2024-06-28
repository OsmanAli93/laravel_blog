<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{

    public function resend (Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'A new verification link has been sent to the email address you provided during registration'], 200);
    }

    public function verify ($id)
    {
        $user = User::findOrFail($id);

        if ( !$user->hasVerifiedEmail() ) {

            $user->markEmailAsVerified();
            event(new Verified($user));

            return redirect('http://localhost:3000/email/verify/success');
        }

        return redirect('http://localhost:3000/email/verify/success');
    }
}

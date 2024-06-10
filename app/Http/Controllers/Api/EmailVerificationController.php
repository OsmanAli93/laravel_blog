<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{

    public function verify ($id)
    {
        $user = User::findOrFail($id);

        if ( !$user->hasVerifiedEmail() ) {

            $user->markEmailAsVerified();
            event(new Verified($user));

            return redirect(url(env('SPA_URL')).'/verified=1');
        }

        return redirect(url(env('SPA_URL')).'/verified=1');
    }
}

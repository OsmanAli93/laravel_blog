<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FollowUnfollowRequest;

class FollowController extends Controller
{
    public function follow(Request $request) {
        // dd($request->id);
        $userToFollow = User::findOrFail($request->id);
        auth()->user()->follow($userToFollow);

        return response()->noContent(200);
    }

    public function unfollow(Request $request) {
        $userToUnfollow = User::findOrFail($request->id);
        auth()->user()->unfollow($userToUnfollow);

        return response()->noContent(200);
    }
}

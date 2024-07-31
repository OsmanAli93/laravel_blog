<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['profile'])->find($id);

        if ( $user ) {

            $posts = Post::with(['user', 'user.profile'])->where('user_id', $id)->latest()->paginate(9);

            return response()->json( [
                'message' => 'Data successfully retrieved',
                'user' => $user,
                'posts' => $posts,
                'total' => $posts->count()
            ], 200);
        }

        return response()->json( [
            'message' => 'User Not Found!'
        ], 404 );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

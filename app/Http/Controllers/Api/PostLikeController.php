<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostLike;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;


class PostLikeController extends Controller
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
    public function store(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if ( $post ) {

            if ( $post->likedByUser($request->user()) ) {

                return response()->json(['message' => 'You already liked this post!'], 419);
            }

            $post->likes()->create([
                'user_id' => $request->user()->id,
            ]);

            if ( !$post->likes()->onlyTrashed()->where('user_id', $request->user()->id)->count() ) {

                Notification::send($request->user(), new PostLike($post, $request->user()->name));
            }

            return response()->json([
                'message' => 'You liked this post',
                'post' => $post->load(['user', 'user.profile', 'likes'])
            ], 201);
        }


        return response()->json([
            'message' => 'Post not found!'
        ], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if ( $post ) {

            $request->user()->likes()->where('post_id', $post->id)->delete();

            return response()->json([
                'message' => 'You unlike this post!',
                'post' => $post->load(['user', 'user.profile', 'likes'])
            ], 201);
        }

        return response()->json(['message' => 'Post not foud!'], 404);
    }
}

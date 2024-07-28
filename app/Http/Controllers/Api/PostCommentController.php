<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreCommentRequest;

class PostCommentController extends Controller
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

            $post->comments()->create([
                'user_id' => $request->user()->id,
                'parent_id' => $request->parent_id,
                'comment' => $request->comment
            ]);

            return response()->json([
                'message' => 'Comment successfully added'
            ], 201);
        }


        return response()->json([
            'message' => 'Post not found!'
        ], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if ( $post ) {

            $comments = $post->comments()->with(['user', 'user.profile'])->where('post_id', $post->id)->latest()->paginate(9);

            return response()->json([
                'message' => 'Data successfully retrieved',
                'comments' => $comments
            ], 200);
        }


        return response()->json([
            'message' => 'Comments not found!'
        ], 404);
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

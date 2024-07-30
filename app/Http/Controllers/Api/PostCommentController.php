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

        $post = Post::where('slug', $slug)->firstOrFail();

        if ( $request->has('parent_id') ) {

            $post->comments()->create([
                'user_id' => $request->user()->id,
                'parent_id' => $request->parent_id,
                'comment' => $request->comment
            ]);

            return response()->json([
                'message' => 'Reply successfully added',
                'comments' => $post->load(['user', 'user.profile', 'comments.user', 'comments.user.profile', 'comments.replies', 'comments.replies.replies', 'comments.replies.replies.user', 'comments.replies.replies.user.profile', 'comments.replies.user', 'comments.replies.user.profile'])
            ], 201);
        }

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $request->comment
        ]);

        return response()->json([
            'message' => 'Comment successfully added',
            'comments' => $post->load(['user', 'user.profile', 'comments.user', 'comments.user.profile', 'comments.replies', 'comments.replies.replies', 'comments.replies.replies.user', 'comments.replies.replies.user.profile', 'comments.replies.user', 'comments.replies.user.profile'])
        ], 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {

        $comments = Post::with(['user', 'user.profile', 'comments.user', 'comments.user.profile', 'comments.replies', 'comments.replies.replies', 'comments.replies.replies.user', 'comments.replies.replies.user.profile', 'comments.replies.user', 'comments.replies.user.profile'])->where('slug', $slug)->firstOrFail();

        return response()->json([
            'message'=> 'Data successfully retrieved',
            'comments' => $comments
        ], 200);
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

<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdateStoreRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['user', 'user.profile', 'likes'])->latest()->paginate(9);

        return response()->json([
            'message' => 'Data retrived successfully',
            'posts' => $posts
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {

        $validated = $request->validated();

        DB::beginTransaction();

        $file = $request->file('thumbnail');
        $extension = $file->getClientOriginalExtension();
        $filename = $request->user()->id . '_' . time() . '.' . $extension;

        $request->user()->posts()->create([
            'thumbnail' => $filename,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title'], '-'),
            'description' => $validated['description'],
            'message' => $validated['message'],
        ]);

        $file->move(public_path('images/thumbnails/'), $filename);

        DB::commit();

        return response()->json([
            'message' => 'Post successfully created',
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $post = Post::with(['user', 'user.profile', 'likes'])->where('slug', $slug)->first();

        if ( $post ) {

            return response()->json([
                'message' => 'Data retrieved successfully',
                'post' => $post
            ], 200);
        }

        return response()->json([
            'message' => 'Post not found!'
        ], 404);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, string $id)
    {

        $validated = $request->validated();

        $post = Post::findOrFail($id);

        DB::beginTransaction();

        if ( $request->hasFile('thumbnail') ) {

            $path = 'images/thumbnails/' . $post->thumbnail;

            if ( File::exists($path) ) {

                File::delete($path);
            }

            $file = $request->file('thumbnail')[0];
            $extension = $file->getClientOriginalExtension();
            $filename = $request->user()->id . '_' . time() . '.' . $extension;

            $post->update([
                'thumbnail' => $filename
            ]);

            $file->move(public_path('images/thumbnails/'), $filename);
        }

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'message' => $validated['message']
        ]);

        DB::commit();

        $posts = $post->where('user_id', $request->user()->id)->paginate(9);

        return response()->json([
            'message' => 'Post successfully updated',
            'posts' => $posts
        ], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $id;

    }
}

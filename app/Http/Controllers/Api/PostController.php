<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;

class PostController extends Controller
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
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        $file = $request->file('thumbnail')[0];
        $extension = $file->getClientOriginalExtension();
        $filename = $request->id . '_' . time() . '.' . $extension;

        $request->user()->posts()->create([
            'thumbnail' => $filename,
            'title' => $validated->title,
            'slug' => Str::slug($validated->title, '-'),
            'description' => $validated->description,
            'message' => $validated->message,
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
    public function destroy(string $id)
    {
        //
    }
}

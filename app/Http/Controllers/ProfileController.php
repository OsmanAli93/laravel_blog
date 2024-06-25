<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
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
        $profile = Profile::with(['user'])->find($id);

        return response()->json([
            'message' => 'Profile Successfully Retrieved',
            'profile' => $profile
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = User::with(['profile'])->find($id);

        DB::beginTransaction();

        if ( $request->hasFile('avatar') ) {

            $path = 'images/avatars/' . $user->profile->avatar;

            if ( File::exists($path) ) {

                File::delete($path);
            }

            $file = $request->file('avatar')[0];
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/avatars/'), $filename);

            $user->profile->update(['avatar' => $filename]);
        }

        if ($request->hasFile('background_image')) {

            $path = 'images/backgrounds/' . $user->profile->background_cover;

            if (File::exists($path)) {

                File::delete($path);
            }

            $file = $request->file('background_image')[0];
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('images/backgrounds/'), $filename);

            $user->profile->update([
                'background_image' => $filename,
            ]);
        }

        $user->profile->update([
            'username' => $request->username,
            'about' => $request->about,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code
        ]);

        DB::commit();

        return response()->json([
            'message' => 'Profile Successfully Updated',
            'user' => $user
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

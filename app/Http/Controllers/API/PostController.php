<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $posts = Post::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->cursorPaginate();
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'mimes:png,jpg,jpeg'
            ]);
            $image = $request->file('image');
            $path = $image->store('post_images');
        }else {
            $request->validate([
                'text' => 'required|min:5'
            ]);
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'text' => $request->text ?? null,
            'image' => $path ?? null,
            'visibility' => $request->visibility ?? 'public'
        ]);

        return response()->json([
            'message' => 'Post Created',
            'data' => $post
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}

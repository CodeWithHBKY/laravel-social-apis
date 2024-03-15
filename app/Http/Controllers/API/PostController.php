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
        $posts = Post::with(['user:id,first_name,last_name', 'comments.user:id,first_name,last_name', 'likes.user:id,first_name,last_name'])
                        ->withCount(['likes', 'comments'])
                        ->where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->cursorPaginate();
        return response()->json($posts);
    }

    public function publicPosts()
    {
        $posts = Post::with(['user:id,first_name,last_name', 'comments.user:id,first_name,last_name', 'likes.user:id,first_name,last_name'])
                        ->withCount(['likes', 'comments'])
                        ->where('visibility', 'public')
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

        $post->load(['user:id,first_name,last_name', 'comments.user:id,first_name,last_name', 'likes.user:id,first_name,last_name'])
            ->loadCount(['likes', 'comments']);

        broadcast(new \App\Events\PostEvent($post));

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
        $request->validate([
            'visibility' => 'in:public,private'
        ]);

        if (  $request->text != $post->text && ($request->text != null || $request->text != '')) {
            $request->validate([
                'text' => 'required|min:5'
            ]);
        }

        $post->update($request->only(['text', 'visibility']));

        return response()->json($post);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'message' => "post delete successfully",
        ]);
    }
}

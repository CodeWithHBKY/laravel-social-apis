<?php

namespace App\Http\Controllers\API;

use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeCommentController extends Controller
{
    public function PostComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required',
            'content' => 'required|min:1|max:250'
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return response()->json($comment);
    }

    public function LikeUnlike(Request $request, $postId)
    {
        $user = $request->user();
        $exists = Like::where('user_id', $user->id)->where('post_id', $postId)->first();
        if ($exists) {
            $exists->delete();
        }else {
            $like = Like::create([
                'user_id' => $user->id,
                'post_id' => $postId,
            ]);
        }


        return response()->json($like ?? Null);
    }
}

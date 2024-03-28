<?php

namespace App\Http\Controllers\API;

use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentNotification;
use App\Notifications\LikeNotifcation;

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
        $comment->load('user:id,first_name,last_name');
        $user = $request->user();

        $post = Post::find($request->post_id);
        $postUser = User::find($post->user_id);
        $title = $user->first_name .' '. $user->last_name. ' Commented on your post.';
        $postUser->notify(new CommentNotification($title, $post));

        broadcast(new \App\Events\CommentEvent($comment))->toOthers();

        return response()->json($comment);
    }

    public function LikeUnlike(Request $request, $postId)
    {
        $user = $request->user();
        $exists = Like::where('user_id', $user->id)->where('post_id', $postId)->first();
        if ($exists) {
            $type = 'unlike';
            $exists->delete();
        }else {
            $type = 'like';
            $like = Like::create([
                'user_id' => $user->id,
                'post_id' => $postId,
            ]);
            $like->load('user:id,first_name,last_name');
            $post = Post::find($postId);
            $postUser = User::find($post->user_id);
            $title = $user->first_name .' '. $user->last_name. ' Liked your post.';
            $postUser->notify(new LikeNotifcation($title, $post));
        }

        $data = [
            'type' => $type,
            'like' => $exists ? ['like_id' => $exists->id, 'post_id' => $exists->post_id] : $like,
        ];

        broadcast(new \App\Events\LikeEvent($data));


        return response()->json([], 200);
    }
}

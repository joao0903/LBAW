<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Events\NewNotificationEvent;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\VoteComment;

class CommentController extends Controller {
    function addComment($id, Request $request) { 
        $postId = $id;
        $post = Post::find($postId);
        $post->increment('popularity');

        $request->validate([
            'comment' => 'required|string|max:1000',
        ], [
            'comment.required' => '❌ The comment field is required.',
            'comment.string' => '❌ The comment must be a string.',
            'comment.max' => '❌ The comment must not exceed 1000 characters.',
        ]);
        
        Comment::create([
            'content' => $request->comment,
            'date' => date('Y-m-d H:i'),
            'likes' => 0,
            'id_post' => $postId,
            'id_user' => Auth::user()->id,
        ]);

        $this->broadcastNotification('comment', 'added', $post->postAuthor->id, $postId);


        return redirect("welcome/post/{$id}")->with('success', '✅ Comment added to this post!');
    }

    public function likeComment($id, Request $request) {
        $comment = Comment::find($request->id);
        $userId = Auth::id();
        $commentId = $comment->id;
        $postId = $comment->post->id;

        $authorCommentID = $comment->commentAuthor->id;
        $author = User::find($authorCommentID);
    
        $existingVote = DB::table('votecomment')->where('id_user', $userId)->where('id_comment', $commentId)->exists();
    
        if ($authorCommentID != $userId) {
            if ($existingVote) {
                // User already liked the comment, remove the like
                DB::table('votecomment')->where('id_user', $userId)->where('id_comment', $commentId)->delete();
                $comment->decrement('likes');
                $author->decrement('reputation');
            } else {
                // User hasn't liked the comment, add the like
                DB::table('votecomment')->insert(['id_user' => $userId, 'id_comment' => $commentId]);
                $comment->increment('likes');
                $author->increment('reputation');
            }
        } else {
            return redirect()->back()->with('error', "❌ You can't like your own Comment.");
            
        }
        return redirect("welcome/post/{$postId}");
    }

    public function deleteComment($id, Request $request) {
        $comment = Comment::find($request->id);
        $post_id = $comment->post->id;

        $post = Post::find($post_id);
        $post->decrement('popularity');

        if (!$comment) {
            return redirect()->back()->with('error', "❌ Couldn't find Comment.");
        }

        $comment->delete();
        return redirect()->back()->with('success', '✅ Comment deleted from this post!');
    }

    public function editComment($id, Request $request) {
        $comment = Comment::find($request->id);
        $userId = Auth::id();
        DB::table('votecomment')->where('id_user', $userId)->where('id_comment', $comment->id)->delete();
    
        if (!$comment) {
            return redirect()->back()->with('error', "❌ Couldn't find Comment.");
        }

        $request->validate([
            'editedComment' => 'required|string|max:1000',
        ], [
            'editedComment.required' => '❌ The comment field is required.',
            'editedComment.string' => '❌ The comment must be a string.',
            'editedComment.max' => '❌ The comment must not exceed 1000 characters.',
        ]);

        $comment->content = $request->input('editedComment');
        $comment->date = date('Y-m-d H:i');
        $comment->likes = 0;
        $comment->save();

        return redirect("welcome/post/$comment->id_post")->with('success', '✅ Comment edited from this post!');
    }

    public function broadcastNotification($type, $action, $userId, $postId)
    {
        $notification = Notification::create([
            'content' => "A $type has been added to your post",
            'type' => $type,
            'id_user' => $userId,
            'id_post' => $postId,
        ]);

        broadcast(new NewNotificationEvent($notification));
    }
}


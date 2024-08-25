<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Comment;
use App\Events\NewNotificationEvent;
use App\Models\Notification;    

class VoteController extends Controller
{
    public function vote($postId, $type) /* type = 1 (like); type = -1 (dislike) */
    {
        $userId = auth()->id(); /* tirar o id do utilizador atual */
        $alreadyVoted = false;
        $post = Post::find($postId);
        $postAuthor = $post->postAuthor;

        $existingVote = Vote::where('id_post', $postId) /* verificar se user já votou */
            ->where('id_user', $userId)
            ->first();

        if ($existingVote) { /* se votou, atualizar o voto */
            $alreadyVoted = true;
            $previousValue = $existingVote->rating;
            if ($type == 1 && $previousValue == 1) { /* if user has already liked and tries to like again, remove the like */
                $existingVote->delete();
                $postAuthor->decrement('reputation', 1);
            } 
            else if ($type == -1 && $previousValue == -1) {
                $existingVote->delete();
                $postAuthor->increment('reputation', 1);
            }
            else {
                $existingVote->rating = $type;
                $existingVote->save();
            }
        } 
        else { /* se n votou, criar o voto */
            Vote::create([
                'id_post' => $postId,
                'id_user' => $userId,
                'rating' => $type,
            ]);
            if($type == 1){
                $this->broadcastNotification('rating', 'upvoted', $postAuthor->id, $postId);
            }

        }

        /* fazer a contagem do total de votos e comments para atualizar popularity de um post */
        $numberVotes = $post->votes()->count(); /* votes() está no Model Post para retirar associações que tem com a tabela Votes */
        $numberComments = $post->comments()->count(); /* comments() está no Model Post para retirar associações que tem com a tabela Comments */
        $post->popularity = $numberVotes + $numberComments;
        $post->save();
    
        if ($alreadyVoted) {
            if ($type == -1 && $previousValue == 1) {
                $this->broadcastNotification('rating', 'upvoted', $postAuthor->id, $postId);
                $postAuthor->decrement('reputation', 2);
            } 
            else if ($type == 1 && $previousValue == -1) {
                $postAuthor->increment('reputation', 2);
            }
        } 
        else {
            if ($type == -1) {
                $postAuthor->decrement('reputation', 1);
            } else {
                $postAuthor->increment('reputation', 1);
            }

        }
    
        $postAuthor->save();

        return redirect("welcome/post/{$postId}");
    }

    public function broadcastNotification($type, $action, $userId, $postId)
    {
    $notification = Notification::create([
        'content' => "Your post has been $action",
        'type' => $type,
        'id_user' => $userId,
        'id_post' => $postId,

    ]);

    broadcast(new NewNotificationEvent($notification));
}
}

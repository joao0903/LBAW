<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Topic;
use App\Models\Vote;
use App\Models\Image;
use App\Models\Save;
use App\Models\Tag;
use App\Models\Notification;
use App\Events\NewNotificationEvent;

class PostController extends Controller
{
    function showPost($id) {
        $post = Post::with('topics', 'tags')->find($id);

        if (!$post) {
            echo "Couldn't find post.\n";
            return redirect()->back();

        }

        $image = Image::where('id_post', $id)->first();
        $comments = Comment::where('id_post', $id)->orderBy('date', 'desc')->get();
        $likes = $this->countPostLikes($id);
        $dislikes = $this->countPostDislikes($id);
        $existingVote = NULL;

        if (Auth::check()){
            $existingVote = Vote::where('id_post', $post->id)->where('id_user', Auth::id())->first();
            $save = Save::where([
                ['id_post', $id],
                ['id_user', Auth::user()->id]
            ])->first();
        } else {
            $save = NULL;
        }

        if ($existingVote) {
            $existingVoteRating = $existingVote->rating;
        } else {
            $existingVoteRating = NULL;
        }
        return view('pages.article', ['post' => $post, 'image' => $image, 'save' => $save,'comments' => $comments, 'likes' => $likes, 'dislikes' => $dislikes, 'existingVoteRating' => $existingVoteRating]);
    }

    function countPostLikes($postId) {
        $likeCount = Vote::where('id_post', $postId)
                        ->where('rating', 1)
                        ->count();
    
        return $likeCount;
    }

    function countPostDislikes($postId) {
        $dislikeCount = Vote::where('id_post', $postId)
                        ->where('rating', -1)
                        ->count();
    
        return $dislikeCount;
    }

    function toprecentPosts(){
        $p = Post::all();
        $pposts= $p->sortBy('popularity');
        $topposts = $pposts->reverse()->take(3);
        $dposts= $p->sortBy('date');
        $recentposts = $dposts->reverse()->take(3);
        $highlightedPost = Post::inRandomOrder()->first();

        return view('pages.welcome', ['recentposts' => $recentposts,'topposts' => $topposts, 'highlightedPost' => $highlightedPost]);
        
    }
    function topPosts(){
        $p = Post::all();
        $pposts= $p->sortBy('popularity');
        $topposts = $pposts->reverse();
        
        return view('pages.top', ['topposts' => $topposts]);
        
    }
    function recentPosts(){
        $p = Post::all();
        $dposts= $p->sortBy('date');
        $recentposts = $dposts->reverse();
        

        return view('pages.recent', ['recentposts' => $recentposts]);
        
    }

    function showCreatePost() {
        $topics = Topic::where('type', 'Accept')->get();
        return view('pages.createArticle', ['topics' => $topics]);
    }

    function createPost(Request $request) {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'string|max:10000',
        ], [
            'title.required' => '❌ The title field is required.',
            'title.string' => '❌ The title must be a string.',
            'title.max' => '❌ The title must not exceed 100 characters.',
            'description.string' => '❌ The description must be a string.',
            'description.max' => '❌ The description must not exceed 10,000 characters.',
        ]);        

        $post = Post::create([
            'date' =>  date('Y-m-d H:i'),
            'popularity' => $request->popularity,
            'title' => $request->title,
            'description' => $request->description,
            'id_user' => Auth::user()->id,
            'id_topic' => $request->topic,
        ]);

        if ($request->hasFile('imagepath')) {
            $image = $request->file('imagepath');

            $image_name = time() . rand(1, 99) . '.png';
            $image->move(public_path('/images/articles'), $image_name);

            $imagePath = '/images/articles/' . $image_name;

            $image = Image::create([
                'imagepath' => $imagePath,
                'typeofimage' => 'Post',
                'id_post' => $post->id,
            ]);
        } else {
            return redirect()->back()->with('error', '❌ An image is required.');
        }

        return redirect()->route('welcome')->withSuccess('✅ Your post was created successfully!');
    }

    function showEditPost($id) {
        $post = Post::find($id);
        $topics = Topic::where('type', 'Accept')->get();

        if (!$post) {
            echo "Couldn't find Post.\n";
            return redirect()->back();
        }

        return view('pages.editArticle', ['post' => $post, 'topics' => $topics]);
    }

    function editPost(Request $request) {
        $post = Post::find($request->id);

        if (!$post) {
            echo "Couldn't find Post.\n";
            return redirect()->back();
        }

        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'string|max:10000',
        ], [
            'title.required' => '❌ The title field is required.',
            'title.string' => '❌ The title must be a string.',
            'title.max' => '❌ The title must not exceed 100 characters.',
            'description.string' => '❌ The description must be a string.',
            'description.max' => '❌ The description must not exceed 10,000 characters.',
        ]);

        $post->title = $request->title;
        $post->id_topic = $request->topic;
        $post->description = $request->description;

         // Check if a new image is provided
        if ($request->hasFile('imagepath')) {
            $post->image->delete();
            // Upload and save the new image
            $image = $request->file('imagepath');

            $image_name = time() . rand(1, 99) . '.png';
            $image->move(public_path('/images/articles'), $image_name);

            $imagePath = '/images/articles/' . $image_name;

            $image = Image::create([
                'imagepath' => $imagePath,
                'typeofimage' => 'Post',
                'id_post' => $post->id,
            ]);

            // Associate the new image with the post
            $post->image()->save($image);
        }

        $post->save();
        $comments = Comment::where('id_post', $request->id)->get();
        $likes = $this->countPostLikes($request->id);
        $dislikes = $this->countPostDislikes($request->id);
        if (Auth::check()){
            $save = Save::where([
                ['id_post', $post->id],
                ['id_user', Auth::user()->id]
            ])->first();
        } else $save = NULL;
        $this->broadcastNotification('post', 'edited', $post->id_user, $post->id);


        return redirect()->route('post.show',['id' => $post->id])->withSuccess('✅ Your post was edited successfully!');
    }

    function deletePost(Request $request) {
        $post = Post::find($request->id);
    
        if (!$post) {
            echo "Couldn't find Post.\n";
            return redirect()->back();
        }
        $postTitle = $post->title; 

        $userId = $post->id_user;
        $postId = $post->id;
    
        $post->delete();
        $this->deleteBroadcastNotification('post', 'deleted', $userId, $postId, $postTitle);
        return redirect()->route('welcome')->withSuccess('✅ Your post was deleted successfully!');
       
    }

    function addtag($id, Request $request) {
        $post = Post::find($id);

        if ($post->tags->contains($request->input('tag'))) {
            return redirect()->back()->with('error', '❌ Tag is already associated with the post.');
        }

        $post->tags()->attach($request->input('tag'));
        return redirect()->back()->with('success', '✅ Tag added successfully.');
    }
    
    function deleteTag($id, $idTag) {
        $post = Post::find($id);

        $post->tags()->detach($idTag);
        return redirect()->back()->with('success', '✅ Tag deleted successfully.');
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

    public function deleteBroadcastNotification($type, $action, $userId, $postId, $postTitle)
    {


        $notification = Notification::create([
            'content' => "Your post '{$postTitle}' has been $action",
            'type' => $type,
            'id_user' => $userId,
            'id_post' => null,
        ]);

        broadcast(new NewNotificationEvent($notification));
    }
    
}

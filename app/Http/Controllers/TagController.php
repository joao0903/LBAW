<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Tag;

class TagController extends Controller
{
    function showTagNews($id){
        $tag = Tag::find($id);
        $posts = Post::whereHas('tags', function ($query) use ($id) {
            $query->where('id', $id);
        })->orderBy('date', 'desc')->get();

        return view('pages.tags', ['posts' => $posts,'tag'=>$tag]);
    }
    
    function followtag($id) {
        $tagToFollow = Tag::find($id);

        if (!$tagToFollow) {
            return redirect()->back();
        }
        $tagToFollow->users()->attach(Auth::user());

        return redirect()->back();
    }
    function unfollowtag($id) {
        $tagToUnfollow = Tag::find($id);

        if (!$tagToUnfollow) {
            return redirect()->back();
        }
        $tagToUnfollow->users()->detach(Auth::user());

        return redirect()->back();
    }
}
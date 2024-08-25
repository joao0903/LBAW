<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function delete(User $user, Comment $comment) 
    {
        return Auth::check() && (Auth::user()->isAdmin() || Auth::user()->id == $comment->id_user);
    }

    public function edit(User $user, Comment $comment) 
    {
        return Auth::check() && Auth::user()->id == $comment->id_user;
    }

    public function create() 
    {
        return Auth::check();
    }

    public function like() {
        return Auth::check();
    }

}

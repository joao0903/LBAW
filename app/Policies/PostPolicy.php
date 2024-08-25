<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        
    }

    /**
     * Determine if all posts can be listed by a user.
     */
    public function list(User $user): bool
    {
        // Any (authenticated) user can list its posts.
        return Auth::check();
    }

    /**
     * Determine if a post can be created by a user.
     */
    public function create(User $user): bool
    {
        // Any user can create a new post.
        return Auth::check();
    }

    public function save(User $user): bool
    {
        // Any user can create a new post.
        return Auth::check();
    }

    /**
     * Determine if a user can update a post.
     */
    public function update(User $user, Post $post): bool
    {
        // User can only update posts they own.
        return $user->id === $post->id_user;
    }

    /**
     * Determine if a user can delete a post.
     */
    public function delete(User $user, Post $post): bool
    {
        // User can only delete posts they own.
        return $user->id === $post->id_user;
    }
}

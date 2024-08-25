<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function editUser(User $user)
    {
      return $user->id == Auth::user()->id;
    }

    public function follow(User $user) 
    {
        return Auth::user()->id !== $user->id;
    }
  
    public function unfollow(User $user) 
    {
        return Auth::user()->id !== $user->id;
    }

}

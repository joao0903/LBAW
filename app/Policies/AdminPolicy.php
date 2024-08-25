<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class AdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        
    }

    public function ban_user() 
    {
        return Auth::check() && Auth::user()->isAdmin();
    } 

    public function unban_user() 
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public function delete_user() 
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public function edit_user() 
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public function delete_post() 
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    public function edit_post() 
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\View\View;

use App\Models\User;
use App\Models\Image;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|min:4|confirmed',
            'firstName' => 'required|string|max:250',
            'lastName' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users'
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'firstname' => $request->firstName,
            'lastname' => $request->lastName,
            'email' => $request->email,       
        ]);

        Image::create([
            'imagepath' => 'images/profile.jpg',
            'typeofimage' => 'User',
            'id_user' => $user->id,
        ]);

        return redirect()->route('login')
            ->withSuccess('✅ You have successfully registered to Feup Times!');
    }
}

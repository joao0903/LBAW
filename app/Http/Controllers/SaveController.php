<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

use App\Models\Save; 
use App\Models\Post;

class SaveController extends Controller
{
    public function savePost($id_post) {
        $id_user = Auth::user()->id;

        // Check if the record already exists
        $existingSave = Save::where('id_post', $id_post)->where('id_user', $id_user)->first();

        // If the record doesn't exist, create a new one
        if (!$existingSave) {
            Save::create([
                'id_post' => $id_post,
                'id_user' => $id_user,
            ]);

            return redirect()->back()->with('success', '✅ Post saved successfully!');
        }

        return redirect()->back()->with('error', '❌ Post is already saved.');
    }

    public function saveDeletePost($id_post) {
        // Get the authenticated user's ID
        $id_user = Auth::user()->id;

        // Find the save record or throw an exception
        Save::where('id_post', $id_post)
        ->where('id_user', $id_user)
        ->delete();

        return redirect()->back()->with('success', '✅ Post unsaved successfully!');
    }

}


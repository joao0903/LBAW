<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Ban;
use App\Models\Post;
use App\Models\Comment;


class UserController extends Controller
{
    function showUsers() {
        $users = User::orderBy('id')->paginate(5);
        return view('pages.userManagement', ['users' => $users]);
    }

    function deleteUser($id) {
        $user = User::find($id);

        Post::where('id_user', $id)->update(['id_user' => NULL]);
        Comment::where('id_user', $id)->update(['id_user' => NULL]);
        /*
        $posts = Post::all();
        dd($posts);
        */
        $user->delete();

        return redirect('userManagement')->withSuccess('✅ User deleted successfully!');
    }

    function showUserInfo($id) {
        $user = User::find($id);

        return view('pages.editUser', ['user' => $user]);
    }

    function editUserInfo(Request $req) {
        $user = User::find($req->id);

        $user->firstname = $req->firstName;
        $user->lastname = $req->lastName;
        $user->username = $req->username;
        $user->email = $req->email;
        $user->save();

        return redirect('userManagement')->withSuccess('✅ User edited successfully!');
    }

    public function createUser(Request $req) {
        $existingUserEmail = User::where('email', $req->email)->first();
        if($existingUserEmail) {
            return redirect('userManagement')->with('error', '❌ User with this email already exists!');
        }

        $existingUserUsername = User::where('username', $req->username)->first();
        if($existingUserUsername) {
            return redirect('userManagement')->with('error', '❌ User with this username already exists!');
        }

        $user = new User();

        $user->firstname = $req->firstName;
        $user->lastname = $req->lastName;
        $user->username = $req->username;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);
        
        $user->save();

        return redirect('userManagement')->withSuccess('✅ User created successfully!');
    }
    public function searchUser(Request $request)
    {
        $query = $request->input('username');
        if (empty($query)) {
            return redirect()->back();
        }
        $tsQueryTerms = array_map(function ($term) {
            return "'$term'";
        }, explode(' ', $query));
        $tsQuery = implode(' & ', $tsQueryTerms);
        $exactMatchResults = User::where('username', 'ILIKE', $query);
        $ftsResults = User::whereRaw("username_tsvectors @@ to_tsquery('english', ?)", [$tsQuery])
            ->orWhere('username', 'ILIKE', '%' . $query . '%');
        
        $finalResults = $exactMatchResults->union($ftsResults)->paginate(5);
    
        return view('pages.userManagement', ['users' => $finalResults]);
    }
    

    public function userBanForm($userId){
        $user = User::find($userId);
        return view('pages.banForm', ['user' => $user]);    
    }

    public function userBan(Request $request, $userId){
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        $user = User::find($userId);
        $reason = $request->input('reason');
        Ban::create(['reason' => $reason, 'id_user'=>$userId]);
        return redirect('userManagement')->with('success', '✅ User Banned');

    }
    public function userUnban(Request $request, $userId){
        $user = User::find($userId);
        $ban = $user->ban()->first();
        if ($ban) {
            $ban->delete();
            return redirect()->route('userManagement.showUsers')->with('success', '✅ User Unbanned');
        } else {
            return redirect()->route('userManagement.showUsers')->with('error', '❌ User is not currently banned');
        }
    }

    


}

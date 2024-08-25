<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Image;
use App\Models\Post;
use App\Models\Save;
use App\Models\Tag;
use App\Models\Ban;

class ProfileController extends Controller
{
    function showProfile($id) {
        $user = User::find($id);
        $followedUsers = $user->followedUsers()->paginate(5);
        $tags = $user->followedTags;

        if ($user && $user->isBanned() && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', '❌ This user is banned.'); //acrescentar aqui uma notificaçao de user is banned cannot see profile   
        }

        if (!$user) {
            echo "Couldn't find user.\n";
            return redirect()->back();
        }
        
        $followingUsers = $user->followingUsers->count();
        $image = Image::where('id_user', $id)->first();

        
        return view('pages.profile', ['user' => $user, 'image' => $image, 'followedUsers' => $followedUsers ,'followingUsers'=> $followingUsers,'tags' => $tags]);
    }
    
    function showEditProfile($id) {
        $user = User::find($id);

        if (!$user) {
            echo "Couldn't find user.\n";
            return redirect()->back();
        }

        return view('pages.editProfile', ['user' => $user]);
    }

    function update(Request $req) {
        $user = User::find($req->id);
        
        if (!$user) {
            return redirect()->back()->with('error', "❌ Couldn't find user.");;
        }

        $req->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'firstName' => 'required|string|max:250',
            'lastName' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users,email,' . $user->id,
        ], [
            'username.required' => '❌ The username field is required.',
            'username.string' => '❌ The username must be a string.',
            'username.max' => '❌ The username must not exceed 255 characters.',
            'username.unique' => '❌ The username is already taken.',
            'firstName.required' => '❌ The first name field is required.',
            'firstName.string' => '❌ The first name must be a string.',
            'firstName.max' => '❌ The first name must not exceed 250 characters.',
            'lastName.required' => '❌ The last name field is required.',
            'lastName.string' => '❌ The last name must be a string.',
            'lastName.max' => '❌ The last name must not exceed 250 characters.',
            'email.required' => '❌ The email field is required.',
            'email.email' => '❌ The email must be a valid email address.',
            'email.max' => '❌ The email must not exceed 250 characters.',
            'email.unique' => '❌ The email is already taken.',
        ]);

        $user->firstname = $req->firstName;
        $user->lastname = $req->lastName;
        $user->username = $req->username;
        $user->email = $req->email;   

        if ($req->hasFile('imagepath')) { 
            
            $user->image->delete();
            $image = $req->file('imagepath');

            $image_name = time() . rand(1, 99) . '.png';
            $image->move(public_path('/images/profiles'), $image_name);

            $imagePath = '/images/profiles/' . $image_name;

            $image = Image::create([
                'imagepath' => $imagePath,
                'typeofimage' => 'User',
                'id_post' => $user->id,
            ]);

            $user->image()->save($image);
        } else {
             return redirect()->back()->with('error', '❌ No image selected!');
        }

        $user->save();
        $followedUsers = $user->followedUsers()->paginate(5);
        $followingUsers = $user->followingUsers->count();
        $tags = $user->followedTags;

        return redirect()->route('profile.show',['id' => $user->id])->withSuccess('✅ Your profile was edited successfully!');
    }

    public function follow($id)
    {
        $userToFollow = User::find($id);
        auth()->user()->followedUsers()->attach($userToFollow);

        return redirect()->back();
    }

    public function unfollow($id)
    {
        $userToUnfollow = User::find($id);
        auth()->user()->followedUsers()->detach($userToUnfollow);

        return redirect()->back();
    }
    function showUserPosts($id) {
        $posts = Post::where('id_user',$id )->get();
        $user = User::find($id);

        return view("pages.userposts", ['posts' => $posts,'user'=>$user]);;
    }
    function showUserSaved($id) {
        $saved = Save::where('id_user',$id)->get('id_post');
        $user= User::find($id);
        $posts = collect([]);
        foreach ($saved as $s) {
                $p= Post::find($s)->first();
                $posts->add($p);
        }

        return view("pages.usersaved", ['posts' => $posts,'user'=>$user]);
        }
        public function userBanFormProfile($userId){
            $user = User::find($userId);
            return view('pages.banFormProfile', ['user' => $user]);    
        }
        public function userBanProfile(Request $request, $id){
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);
            $user = User::find($id);
            $reason = $request->input('reason');
            Ban::create(['reason' => $reason, 'id_user'=>$id]);
            return redirect()->route('profile.show', ['id' => $id])->with('success', '✅ User Banned');
    
        }
        public function userUnbanProfile(Request $request, $id){
            $user = User::find($id);
            $ban = $user->ban()->first();
            if ($ban) {
                $ban->delete(); 
                return redirect()->back()->with('success', '✅ User Unbanned'); //NOTS 
            } else {
                return redirect()->back()->with('error', '❌ User is not currently banned'); // NOTS MAS POSSIVELMENTE VOU APGAR ISTO DEOPIS
            }
        }
}

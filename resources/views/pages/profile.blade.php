@extends('layouts.app')

@section('title', 'FEUP Times - Profile Page')  

@section('content')  
<h4>
    @if (session('success'))
        <span class="success">
            {{ session('success') }}
        </span>
    @else
        <span class="success">
            {{ session('error') }}
        </span>
    @endif
</h4>
<div class="profile-page-container">
    <section class="profile-options">
        <h2 id="profile-options-title">Profile Options</h2>
        <ul>
            @if ((Auth::check()) && $user->id == Auth::user()->id)
                <li><a href="/profile/{{$user->id}}/edit">Edit Profile</a></li>
            @endif
            <li><a href="/profile/{{$user->id}}/posts">Submited Posts</a></li>
            <li><a href="/profile/{{$user->id}}/saved">Saved Posts</a></li>
            @if ((Auth::check()) && $user->id == Auth::user()->id)
                <li><a href="{{ url('/logout') }}">Logout</a></li>
            @endif
        </ul>
    </section>
    <section class="profile-info">
        <div class="profile-actions">
            @if (Auth::check() && Auth::user()->isAdmin())
                @if ($user->isBanned())
                    <form action="{{ route('userUnbanProfile', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="follow-unfollow" type="submit">Unban</button>
                @else
                    <a class="button" href="{{ "/profile/ban/$user->id"}}">BAN</a>
                @endif
            @endif
            @if (Auth::check() && Auth::user()->id !== $user->id)
                @if (Auth::user()->followedUsers->contains($user->id))
                    <form action="{{ route('unfollow', $user->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="follow-unfollow" type="submit">UNFOLLOW</button>
                    </form>
                @else
                    <form action="{{ route('follow', $user->id) }}" method="post">
                        @csrf
                        <button class="follow-unfollow" type="submit">FOLLOW</button>
                    </form>
                @endif
            @endif
        </div>
        <div class="profile-image">
            <img src="{{ asset($image->imagepath) }}" alt="Profile Picture">
        </div>
        <h1 id="name"><span>Name: </span>{{$user->firstname . ' ' . $user->lastname}}</h1>
        <h2 id="username"><span>Username: </span>{{$user->username}}</h2>
        <h3 id="reputation"><span>Reputation: </span>{{$user->reputation}}</h3> 
        <h4 id="followers"><span>Followers: </span>{{$followingUsers}}</h4>
    </section>
</div>
<div class="followed-container">
    <section class="followed-journalists">
        <h2 class="followed-journalists-title">Followed Journalists</h2>
        <ul>
            @forelse ($followedUsers as $followedUser)
                <li>
                    <a href={{ "/profile/$followedUser->id" }}>{{ $followedUser->username }}</a>
                </li>
            @empty 
                <li>You currently are not following any Journalists!</li>
            @endforelse
        </ul>
        <span class="pagination">
            {{ $followedUsers->links() }}
        </span>
    </section>
</div>
<div class="followed-container">
    <section class="followed-journalists">
        <h2 class="followed-journalists-title">Followed Tags</h2>
        <ul>
            @forelse ($tags as $tag)
                <li><a href={{ "/welcome/tag/$tag->id" }}> {{$tag->title}} </a></li>
            @empty 
                <li>You currently are not following any Tags!</li>
            @endforelse
        </ul>
    </section>
</div>
@endsection
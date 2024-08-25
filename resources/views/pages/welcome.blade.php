@extends('layouts.app')

@section('title', 'FEUP Times - Home')

@section('content')
@if (Auth::check())
    <div class="main-page-operations">
        <a href="{{ url('/welcome/createPost') }}">
            Create Post 
            <svg class="icons" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 6C12.5523 6 13 6.44772 13 7V11H17C17.5523 11 18 11.4477 18 12C18 12.5523 17.5523 13 17 13H13V17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17V13H7C6.44772 13 6 12.5523 6 12C6 11.4477 6.44772 11 7 11H11V7C11 6.44772 11.4477 6 12 6Z" fill="currentColor" /><path fill-rule="evenodd" clip-rule="evenodd" d="M5 22C3.34315 22 2 20.6569 2 19V5C2 3.34315 3.34315 2 5 2H19C20.6569 2 22 3.34315 22 5V19C22 20.6569 20.6569 22 19 22H5ZM4 19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19V5C20 4.44772 19.5523 4 19 4H5C4.44772 4 4 4.44772 4 5V19Z" fill="currentColor" /></svg>
        </a>
        <a href="{{ url('/proposeTopic') }}">
            Propose a topic to FEUP Times 
            <svg class="icons" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 11C7.44772 11 7 11.4477 7 12C7 12.5523 7.44772 13 8 13H15.9595C16.5118 13 16.9595 12.5523 16.9595 12C16.9595 11.4477 16.5118 11 15.9595 11H8Z" fill="currentColor" /><path d="M8.04053 15.0665C7.48824 15.0665 7.04053 15.5142 7.04053 16.0665C7.04053 16.6188 7.48824 17.0665 8.04053 17.0665H16C16.5523 17.0665 17 16.6188 17 16.0665C17 15.5142 16.5523 15.0665 16 15.0665H8.04053Z" fill="currentColor" /><path fill-rule="evenodd" clip-rule="evenodd" d="M5 3C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H5ZM7 5H5L5 19H19V5H17V6C17 7.65685 15.6569 9 14 9H10C8.34315 9 7 7.65685 7 6V5ZM9 5V6C9 6.55228 9.44772 7 10 7H14C14.5523 7 15 6.55228 15 6V5H9Z" fill="currentColor" /></svg>
        </a>
    </div>
@endif
<h4>
    @if (session('success'))
        <span class="success">
            {{ session('success') }}
        </span>
    @endif
</h4>
<div class="mainfeed">
    <article class="recent-news">
        <h2><a href="/welcome/recent">Recent News</a></h2>
        <ul>
            @foreach ($recentposts as $recentpost)
                <li>
                    <a href="/welcome/post/{{$recentpost->id}}">
                        <div class="centered-image-top">
                            <img src=" {{asset($recentpost->image->imagepath)}}" alt="Recent Article Image">
                        </div>
                        <h3>{{$recentpost->title}}</h3>
                    </a>
                </li>   
            @endforeach
        </ul>
    </article>
    <article class="random-new">
        <a href="/welcome/post/{{ $highlightedPost->id }}">
        <h2>Highlighted Article</h2>
        <img src=" {{asset($highlightedPost->image->imagepath)}}" alt="Highlighted Image Article">
        <div class="topic-date"><a href="/welcome/newsbytopic/{{ $highlightedPost->topics->id }}">{{ $highlightedPost->topics->title }}</a>{{ $highlightedPost['date'] }}</div>
        <h3>{{ $highlightedPost->title }}</h2>
        <div class="">
            <p>{{ $highlightedPost->description }}</p>
        </div>
        </a>
    </article>
    <article class="top-news">
        <h2><a href="/welcome/top">Top News</h2>
        <ul>
            @foreach($topposts as $toppost)
                <li>
                    <a href="/welcome/post/{{$toppost->id}}">
                        <div class="centered-image-top">
                            <img src="{{asset($toppost->image->imagepath)}}" alt="Top Article Image">
                        </div>
                        <h3>{{$toppost->title}}</h3>
                    </a>
                </li>
            @endforeach
        </ul>
    </article>
</div>
@endsection

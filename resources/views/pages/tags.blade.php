@extends('layouts.app')

@section('title', 'FEUP Times - News By Tags')

@section('content')
<div class="container">
    <div class="follow-tag">
    @if (Auth::check())
        @if (!Auth::user()->followedTags->contains($tag->id))
            <form action="{{ route('followtag', $tag->id) }}" method="post">
                @csrf
                @method('POST')
                <button class="follow-unfollow" type="submit">Follow</button>
            </form>
        @else
            <form action="{{ route('unfollowtag', $tag->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button class="follow-unfollow" type="submit">Unfollow</button>
            </form>
        @endif
    @endif
    </div>
    <h1>{{$tag->title}}</h1>
    <p>{{$tag->description}}</p>
        <div class="mainfeed1">
            <div class="search-results">
                @foreach($posts as $post)
                    <div class="result-box">
                        <a href="{{ url('/welcome/post/' . $post->id) }}">
                            <img src="{{asset($post->image->imagepath)}}" alt="Image Tag Article">
                            <h3>{{ $post->title }}</h3>
                            <p>{{ $post->description }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
</div>

@endsection
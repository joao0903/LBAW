@extends('layouts.app')

@section('title', 'FEUP Times - My Saved Posts')

@section('content')
    <div class="container">
        <h1>My Saved Posts</h1>
            <div class="mainfeed1">
                <div class="search-results">
                    @foreach($posts as $post)
                        <a href="{{ url('/welcome/post/' . $post->id) }}">
                            <div class="result-box">
                                <img src="{{asset($post->image->imagepath)}}" alt="Image Saved Article">
                                <h3>{{ $post->title }}</h3>
                                <p>{{ $post->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
    </div>
@endsection
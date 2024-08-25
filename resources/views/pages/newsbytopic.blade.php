@extends('layouts.app')

@section('title', 'FEUP Times - News By Topic')

@section('content')
    <div class="container">
        <h1>{{$topic->title}}</h1>
        <p>{{$topic->description}}</p>
            <div class="mainfeed1">
                <div class="search-results">
                    @foreach($posts as $post)
                        <a href="{{ url('/welcome/post/' . $post->id) }}">
                            <div class="result-box">
                                <img src="{{asset($post->image->imagepath)}}" alt="Image Article By Topic">
                                <h3>{{ $post->title }}</h3>
                                <p>{{ $post->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
    </div>
@endsection
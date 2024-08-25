@extends('layouts.app')

@section('title', 'FEUP Times - Top News')

@section('content')
    <div class="container">
        <h1>Top News</h1>
            <div class="mainfeed1">
                <div class="search-results">
                    @foreach($topposts as $post)
                        <a href="{{ url('/welcome/post/' . $post->id) }}">
                            <div class="result-box">
                                <img src="{{asset($post->image->imagepath)}}" alt="Image Top Article">
                                <h3>{{ $post->title }}</h3>
                                <p>{{ $post->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
    </div>
@endsection
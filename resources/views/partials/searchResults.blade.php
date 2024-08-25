@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    <div class="container">
        <h1>Search Results for "{{ $query }}"</h1>

        @if($results->isEmpty())
            <p>No results found.</p>
        @else
            <div class="mainfeed1">
                <div class="search-results">
                    @foreach($results as $result)
                        <a href="{{ url('/welcome/post/' . $result->id) }}">
                            <div class="result-box">
                                <img src="{{ asset($result->image->imagepath) }}" alt="Small Image Article">
                                <h3>{{ $result->title }}</h3>
                                <p>{{ $result->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

@endsection

@extends('layouts.app')

@section('title', 'FEUP Times - Create Article')  

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
<div class="create-article-container">
    <h1>Create Article</h1>
    <form class="create-article" action="{{route('createPost')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset>
            <legend>Article Information</legend>
            <label for="title">Title:</label>
            <input id="title" type="text" name="title" placeholder="Insert Title Here" required autofocus>
            @if ($errors->has('title'))
                <span class="error">
                    {{ $errors->first('title') }}
                </span>
            @endif

            <label for="topic">Topic:</label>
            <select id="topic" name="topic">
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                @endforeach
            </select>
            
            <label for="description">Description:</label>
            <input id="description" type="text" name="description" placeholder="Insert News Here">
            @if ($errors->has('description'))
                <span class="error">
                    {{ $errors->first('description') }}
                </span>
            @endif
            
            <label for="imagepath">Image:</label>
            <p id="maximum-image-size-warning">(Maximum Size: 2MB)</p>
            <input id="imagepath" type="file" name="imagepath" accept="image/*" required>
        </fieldset>
        <input type="hidden" name="popularity" value=0>
        <button type="submit">Create</button>
    </form>
</div>
@endsection

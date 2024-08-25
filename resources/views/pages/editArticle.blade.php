@extends('layouts.app')

@section('title', 'FEUP Times - Edit Article')  

@section('content')
<div class="edit-article-container">
    <h1>Edit Article</h1>
    <form class="edit-article" action="/welcome/post/{{$post->id}}/edit" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset>
            <legend>Article Information</legend>
            <input type="hidden" name="id" value="{{ $post->id }}">
            
            <label for="title">Title:</label>
            <input id="title" type="text" name="title" value="{{ $post->title }}" required autofocus>
            @if ($errors->has('title'))
                <span class="error">
                    {{ $errors->first('title') }}
                </span>
            @endif

            <label for="topic">Topic:</label>
            <select id="topic" name="topic">
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" {{ $topic->id == $post->topics->id ? 'selected' : '' }}>{{ $topic->title }}</option>
                @endforeach
            </select>
            
            <label for="description">Description:</label>
            <input id="description" type="text" name="description" value="{{ $post->description }}">
            @if ($errors->has('description'))
                <span class="error">
                    {{ $errors->first('description') }}
                </span>
            @endif
            
            <label for="imagepath">Image:</label>
            <p id="maximum-image-size-warning">(Maximum Size: 2MB)</p>
            <input id="imagepath" type="file" name="imagepath" accept="image/*">
        </fieldset>

        <input type="hidden" name="popularity" value=0>
        <button type="submit">Edit</button>
    </form>
</div>
@endsection

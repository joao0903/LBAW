@extends('layouts.app')

@section('title', 'FEUP Times - Edit Profile')  

@section('content')
<div class="edit-profile-container">
    <h1>Edit Profile</h1>
    <form class="edit-profile" action="/profile/{{ $user->id }}/edit" method="POST" enctype="multipart/form-data">
        @csrf
        <fieldset>
            <legend>Personal Information</legend>
            <input type="hidden" name="id" value="{{ $user->id }}">
            
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" value="{{ $user->firstname }}">
            </div>
            @if ($errors->has('firstName'))
                <span class="error">
                    {{ $errors->first('firstName') }}
                </span>
            @endif

            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" value="{{ $user->lastname }}">
            </div>
            @if ($errors->has('lastName'))
                <span class="error">
                    {{ $errors->first('lastName') }}
                </span>
            @endif

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="{{ $user->username }}">
            </div>
            @if ($errors->has('username'))
                <span class="error">
                    {{ $errors->first('username') }}
                </span>
            @endif

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="{{ $user->email }}">
            </div>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }}
                </span>
            @endif
        </fieldset>

        <div class="form-group">
            <label for="imagepath">Profile Image:</label>
            <p id="maximum-image-size-warning">(Maximum Size: 2MB)</p>
            <input id="imagepath" type="file" name="imagepath" accept="image/*"> 
        </div>
        @if (session('error'))
            <span class="error">
                {{ session('error') }}
            </span>
        @endif
        <br> <br>
        <button type="submit">Update</button>
    </form>
</div>
@endsection

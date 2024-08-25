@extends('layouts.app')

@section('title', 'FEUP Times - Edit User')  

@section('content')
<form class="edit-user-admin" action="/userManagement/edit/{{ $user->id }}" method="POST">
    <h1>Edit User</h1>
    @csrf
    <input type="hidden" name="id" value="{{ $user->id }}">

    <fieldset>
        <legend>User Information</legend>

        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="{{ $user->firstname }}">
        </div>

        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="{{ $user->lastname }}">
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="{{ $user->username }}">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="{{ $user->email }}">
        </div>
    </fieldset>

    <div class="center-button">
        <button type="submit">Edit</button>
    </div>
</form>
@endsection

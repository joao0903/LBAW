@extends('layouts.app')

@section('title', 'FEUP Times - Ban User')  

@section('content')
    <h2 class="ban-user-title">Ban User</h2>
    <form class="edit-user-admin" action="{{ route('userBan', $user->id) }}" method="POST">
        @csrf
        <label class="reason-ban-label" id="ban-reason" for="reason">Reason for Ban:</label>
        <input type="text" name="reason" id="reason" placeholder="Insert reason here" required>
        <button type="submit">Ban User</button>
    </form>
@endsection



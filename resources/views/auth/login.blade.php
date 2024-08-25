@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}" class="form-auth">
    {{ csrf_field() }}

    <label for="email">E-mail</label>
    <input class="fill" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Insert your email here" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password" >Password</label>
    <input class="fill" id="password" type="password" name="password" placeholder="Insert your password here" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <button type="submit">
        Login
    </button>
    <p>Don't have an account? </p>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
@endsection
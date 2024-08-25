@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}" class="form-auth">
    {{ csrf_field() }}

    <label for="username">Username</label>
    <input class="fill" id="username" type="text" name="username" value="{{ old('username') }}" placeholder="Insert your username here" autofocus>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <label for="firstName">First Name</label>
    <input class="fill" id="firstName" type="text" name="firstName" value="{{ old('firstName') }}" placeholder="Insert your first name here" required>
    @if ($errors->has('firstName'))
      <span class="error">
          {{ $errors->first('firstName') }}
      </span>
    @endif

    <label for="lastName">Last Name</label>
    <input class="fill" id="lastName" type="text" name="lastName" value="{{ old('lastName') }}" placeholder="Insert your last name here" required>
    @if ($errors->has('lastName'))
      <span class="error">
          {{ $errors->first('lastName') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <input class="fill" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Insert your email here" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input class="fill" id="password" type="password" name="password" placeholder="Insert your password here" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input class="fill" id="password-confirm" type="password" name="password_confirmation" placeholder="Repeat your password" required>

    <button type="submit">
      Register
    </button>
    <p>Already have an account? </p>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection
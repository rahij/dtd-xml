@extends('layouts.master')

@section('content')
<div class="loginRegisterBox">
  <h1>Login</h1>
  <div class="content">
    <form method="POST" action="/auth/login">
      {!! csrf_field() !!}

      <div class="form-group">
        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
      </div>

      <div class="form-group">
        <input class="form-control" type="password" name="password" id="password" placeholder="Password">
      </div>

      <div class="checkbox">
        <label><input type="checkbox" name="remember">Remember Me</label>
      </div>

      <div class="form-group">
        <input type="submit" value="Login" class="btn btn-primary login" />
      </div>
    </form>
  </div>
</div>
@endsection

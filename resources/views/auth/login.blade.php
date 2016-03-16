@extends('layouts.master')

@section('content')
  <div class="content">
    <form method="POST" action="/auth/login">
      {!! csrf_field() !!}

      <div class="form-group">
        Email
        <input class="form-control" type="email" name="email" value="{{ old('email') }}">
      </div>

      <div class="form-group">
        Password
        <input class="form-control" type="password" name="password" id="password">
      </div>

      <div class="checkbox">
        <label><input type="checkbox" name="remember">Remember Me</label>
      </div>

      <div class="form-group">
        <input type="submit" value="Login" class="btn btn-primary" />
      </div>
    </form>
  </div>
@endsection

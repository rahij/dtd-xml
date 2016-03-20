@extends('layouts.master')

@section('content')
<div class="loginRegisterBox">
  <h1>Register</h1>
  <form method="POST" action="/auth/register">
    {!! csrf_field() !!}

    <div class="form-group">
      <input class="form-control" type="text" name="name" placeholder="Name" value="{{ old('name') }}">
    </div>

    <div class="form-group">
      <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
    </div>

    <div class="form-group">
      <input class="form-control" type="password" name="password" placeholder="Password">
    </div>

    <div class="form-group">
      <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password">
    </div>

    <div class="form-group">
      <input type="submit" value="Register" class="btn btn-primary register" />
    </div>
  </form>
</div>

@endsection

@extends('layouts.master')

@section('content')
  <form method="POST" action="/auth/register">
    {!! csrf_field() !!}

    <div class="form-group">
      Name
      <input class="form-control" type="text" name="name" value="{{ old('name') }}">
    </div>

    <div class="form-group">
      Email
      <input class="form-control" type="email" name="email" value="{{ old('email') }}">
    </div>

    <div class="form-group">
      Password
      <input class="form-control" type="password" name="password">
    </div>

    <div class="form-group">
      Confirm Password
      <input class="form-control" type="password" name="password_confirmation">
    </div>

    <div class="form-group">
      <input type="submit" value="Register" class="btn btn-primary" />
    </div>
  </form>

@endsection

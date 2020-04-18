@extends('layouts.app')

@section('content')
<div class="container">
  <h3>{{ __('User data') }}</h3>
  <dl class="row">
    <dt class="col-sm-3">User name</dt>
    <dd class="col-sm-9">{{ $user->name }}</dd>
    <dt class="col-sm-3">User email</dt>
    <dd class="col-sm-9">{{ $user->email }}</dd>
  </dl>
</div>
@endsection
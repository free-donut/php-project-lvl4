@extends('layouts.app')

@section('content')
<div class="container">
  <h3>{{ __('User data') }}</h3>
  <dl class="row">
    <dt class="col-sm-3">{{ __('Name') }}:</dt>
    <dd class="col-sm-9">{{ $user->name }}</dd>
    <dt class="col-sm-3">{{ __('Email') }}:</dt>
    <dd class="col-sm-9">{{ $user->email }}</dd>
    <dt class="col-sm-3">{{ __('Gender') }}:</dt>
    <dd class="col-sm-9">{{ $user->gender }}</dd>
  </dl>
</div>
@endsection
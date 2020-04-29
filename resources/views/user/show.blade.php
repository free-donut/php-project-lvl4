@extends('layouts.app')

@section('content')
<div class="container">
  <h3>{{ __('User data') }}</h3>
  @auth
    <dl class="row">
      <dt class="col-sm-3">{{ __('Name') }}:</dt>
      <dd class="col-sm-9">{{ $user->name }}</dd>
      <dt class="col-sm-3">{{ __('Email') }}:</dt>
      <dd class="col-sm-9">{{ $user->email }}</dd>
      <dt class="col-sm-3">{{ __('Phone number') }}:</dt>
      <dd class="col-sm-9">{{ $user->phone ?? __('Unfilled') }}</dd>
      <dt class="col-sm-3">{{ __('Gender') }}:</dt>
      <dd class="col-sm-9">{{ $user->gender }}</dd>
      <dt class="col-sm-3">{{ __('Birth Date') }}:</dt>
      <dd class="col-sm-9">{{ $user->birthdate ?? __('Unfilled') }}</dd>
    </dl>
    <a class="btn btn-primary" href="{{ route('users.destroy', $user) }}" data-confirm="are you sure?" data-method="delete" rel="nofollow"  role="button">{{ __('Remove') }}</a>
    <a class="btn btn-primary" href="{{ route('users.edit', $user) }}" role="button" >{{ __('Edit') }}</a>
  @endauth
</div>
@endsection
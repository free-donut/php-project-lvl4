@extends('layouts.app')

@section('content')
<div class="container">
	<div>
        @include('flash::message')
    </div>
    <div class="jumbotron">
        <h1 class="display-4">{{ __('Task Manager') }}</h1>
        <p class="lead">{{ __('Simple implementation of typical task manager') }}</p>
        <hr class="my-4">
        <p>{{ __('Hexlet Project') }}</p>
        <a class="btn btn-primary btn-lg" href="https://hexlet.io" role="button">{{ __('Learn more') }}</a>
    </div>
</div>
@endsection

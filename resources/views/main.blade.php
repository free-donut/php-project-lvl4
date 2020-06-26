@extends('layouts.app')

@section('content')
<div class="container">
	<div>
        @include('flash::message')
    </div>
    <div class="jumbotron">
        <h1 class="display-4">{{ __('Task Manager') }}</h1>
        <p class="lead">{{ __('Simple implementation of typical task manager. Main features:') }}</p>
        <ul>
            <li>{{ __('Registration') }}</li>
            <li>{{ __('Authentication') }}</li>
            <li>{{ __('Task management') }}</li>
            <li>{{ __('Filtration') }}</li>
        </ul>
        <hr class="my-4">
        <p>{{ __('Test Project') }}</p>
    </div>
</div>
@endsection

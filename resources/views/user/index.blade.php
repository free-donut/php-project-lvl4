@extends('layouts.app')

@section('content')
<div class="container">
    <h3>List of users</h3>
    <ul class="list-group">
        @foreach ($users as $user)
            <li class="list-group-item">{{ $user->name }}</li>
        @endforeach
    </ul>
    {{ $users->links() }}
</div>
@endsection

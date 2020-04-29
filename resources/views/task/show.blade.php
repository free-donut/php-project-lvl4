@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('Task') }}</h3>
    <dl class="row">
        <dt class="col-sm-3">{{ __('Task name') }}</dt>
        <dd class="col-sm-9">{{ $task->name }}</dd>
        <dt class="col-sm-3">{{ __('Created At') }}</dt>
        <dd class="col-sm-9">{{ $task->created_at }}</dd>
        <dt class="col-sm-3">{{ __('Creator') }}</dt>
        <dd class="col-sm-9">{{ $task->creator->name }}</dd>
        <dt class="col-sm-3">{{ __('Assignee') }}</dt>
        <dd class="col-sm-9">{{ $task->assignedTo->name }}</dd>
        <dt class="col-sm-3">{{ __('Task status') }}</dt>
        <dd class="col-sm-9">{{ $task->taskStatus->name }}</dd>
        <dt class="col-sm-3">{{ __('Description') }}</dt>
        <dd class="col-sm-9">{{ $task->description }}</dd>
        <dt class="col-sm-3">{{ __('Tags') }}</dt>
        <dd class="col-sm-9">
            @foreach($task->tags as $tag)
            <div>{{ $tag->name }}</div>
            @endforeach

        </dd>
    </dl>
    @auth
        @if($task->creator->id == Auth::id())
            <a class="btn btn-primary" href="{{ route('tasks.destroy', $task) }}" data-confirm="are you sure?" data-method="delete" rel="nofollow"  role="button">{{ __('Remove') }}</a>
        @endif
        <a class="btn btn-primary" href="{{ route('tasks.edit', $task) }}" role="button" >{{ __('Edit') }}</a>
    @endauth
</div>
@endsection
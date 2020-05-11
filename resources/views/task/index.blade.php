@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('List of tasks') }}</h3>
    <div>
        @include('flash::message')
    </div>

    <div class="d-flex mb-2">
        {{Form::open(['url' => route('tasks.index'), 'method' => 'GET', 'class' => 'form-inline'])}}
            {{ Form::select('filter[status_id]', $statuses, null, ['class' => 'form-control mr-2', 'placeholder' => __('Status')]) }}
            {{ Form::select('filter[tag_id]', $tags, null, ['class' => 'form-control mr-2', 'placeholder' => __('Tag')]) }}
            {{ Form::select('filter[creator_id]', $creators, null, ['class' => 'form-control mr-2', 'placeholder' => __('Creator')]) }}
            {{ Form::select('filter[assigned_to_id]', $creators, null, ['class' => 'form-control mr-2', 'placeholder' => __('Assignee')]) }}
            {{Form::submit(__('Apply'), ['class' => 'btn btn-outline-primary ml-auto mr-2'])}}
            {{Form::reset(__('Reset'), ['class' => 'btn btn-outline-primary ml-auto mr-2'])}}
        {{Form::close()}}
        @auth
            <a class="btn btn-primary ml-auto" href="{{ route('tasks.create') }}" role="button">{{ __('Add new task') }}</a>
        @endauth
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">{{__('Id') }}</th>
                <th scope="col">{{__('Name') }}</th>
                <th scope="col">{{__('Created At') }}</th>
                <th scope="col">{{__('Status') }}</th>
                <th scope="col">{{__('Creator') }}</th>
                <th scope="col">{{__('Assignee') }}</th>
                @auth
                    <th scope="col">{{__('Actions') }}</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr>
                    <th scope="row">{{ $task->id }}</th>
                    <td><a href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a></td>
                    <td>{{ $task->created_at }}</td>
                    <td>{{ $task->taskStatus->name }}</td>
                    <td>{{ $task->creator->name }}</td>
                    <td>{{ $task->assignedTo->name }}</td>
                    @auth
                        <td>
                            @if($task->creator->id == Auth::id())
                                <a href="{{ route('tasks.destroy', $task) }}" data-confirm="are you sure?" data-method="delete" rel="nofollow">{{ __('Remove') }}</a>
                            @endif
                            <a href="{{ route('tasks.edit', $task) }}">{{ __('Edit') }}</a>
                        </td>
                    @endauth
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tasks->links() }}
</div>
@endsection

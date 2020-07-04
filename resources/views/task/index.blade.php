@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('List of tasks') }}</h3>
    @if ($errors->any())
       @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">{{ $error }}</div>
        @endforeach
    @endif
    <div class="d-flex mb-2">
        @include('task.filter_form')
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

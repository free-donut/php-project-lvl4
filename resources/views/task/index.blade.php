@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('List of tasks') }}</h3>
    <div>
        @include('flash::message')
    </div>
    
    <!-- filter form-->
    <div class="d-flex mb-2">
        <form method="GET" action="{{ route('tasks.index') }}" accept-charset="UTF-8" class="form-inline">
            <select class="form-control" name="filter[status_id]">
                <option value="">{{ __('Status') }}</option>
                @foreach ($statuses as $status)
                <!-- <option selected="selected" value="">Status</option>-->
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
            </select>
            
            <select class="form-control mr-2" name="filter[creator_id]">
                <option value="">{{ __('Creator') }}</option>
                @foreach ($creators as $creator)
                    <option value="{{ $creator->id }}">{{ $creator->name }}</option>
                @endforeach
            </select>
            
            <select class="form-control mr-2" name="filter[assigned_to_id]">
            <option value="">{{ __('Assignee') }}</option>
            @foreach ($assignees as $assignee)
                <option value="{{ $assignee->id }}">{{ $assignee->name }}</option>
            @endforeach
        </select>
                <input class="btn btn-outline-primary" type="submit" value="Apply">
        </form>
        @auth
            <a class="btn btn-primary ml-auto" href="{{ route('tasks.create') }}" role="button">{{ __('Add new task') }}</a>
        @endauth
    </div>
    <!-- end filter form-->
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

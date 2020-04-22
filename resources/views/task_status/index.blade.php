@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('List of task statuses') }}</h3>
    <div>
        @include('flash::message')
    </div>
    @auth
        <a class="btn btn-primary btn-lg" href="{{ route('task_statuses.create') }}" role="button">{{ __('Add new task status') }}</a>
    @endauth
    <table class="table">
        <thead>
            <tr>
                <th scope="col">{{__('Id') }}</th>
                <th scope="col">{{__('Name') }}</th>
                <th scope="col">{{__('Created At') }}</th>
                @auth
                    <th scope="col">{{__('Actions') }}</th>
                @endauth
            </tr>
        </thead>
        <tbody>
            @foreach ($taskStatuses as $taskStatus)
                <tr>
                    <th scope="row">{{ $taskStatus->id }}</th>
                    <td>{{ $taskStatus->name }}</td>
                    <td>{{ $taskStatus->created_at }}</td>
                    @auth
                        <td>
                            <a href="{{ route('task_statuses.destroy', $taskStatus) }}" data-confirm="are you sure?" data-method="delete" rel="nofollow">{{ __('Remove') }}</a>
                            <a href="{{ route('task_statuses.edit', $taskStatus) }}">{{ __('Edit') }}</a>
                        </td>
                    @endauth
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $taskStatuses->links() }}
</div>
@endsection

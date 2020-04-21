@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create new Task status') }}</div>

                <div>
                    @include('flash::message')
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @method('PATCH')
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $task->name}}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required autocomplete="description" autofocus>{{ old('description') ?? $task->description}}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="taskStatus" class="col-md-4 col-form-label text-md-right">{{ __('Task Status') }}</label>
                            <div class="col-md-6">
                                <select class="form-control" name="status_id" id="taskStatus">
                                    @foreach ($taskStatuses as $taskStatus)
                                        @if($taskStatus->id = $task->taskStatus->id)
                                            <option value="{{ $taskStatus->id }}" selected="selected">{{ $taskStatus->name }}</option>
                                        @else
                                            <option value="{{ $taskStatus->id }}">{{ $taskStatus->name }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                @error('taskStatus')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="assignee" class="col-md-4 col-form-label text-md-right">{{ __('Assignee') }}</label>
                            <div class="col-md-6">
                                <select class="form-control" name="assigned_to_id" id="assignee">
                                    @foreach ($assignees as $assignee)
                                        <option value="{{ $assignee->id }}">{{ $assignee->name }}</option>
                                    @endforeach
                                </select>

                                @error('assignee')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

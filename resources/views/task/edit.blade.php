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
                    {{ Form::model($task, ['url' => route('tasks.update', $task), 'method' => 'PATCH']) }}
                        {{Form::token()}}
                        @include('task.form')

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

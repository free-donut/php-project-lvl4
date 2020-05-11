<div class="form-group row">
    {{ Form::label('name', __('Name'), ['class' => 'col-md-4 col-form-label text-md-right']) }}
    <div class="col-md-6">
    {{ 
        Form::text(
            'name', 
            old('name'), 
            ['class' => 'form-control' . ( $errors->has('name') ? ' is-invalid' : '' ), 'required' => 'required']
        ) 
    }}

        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
           </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label('description', __('Description'), ['class' => 'col-md-4 col-form-label text-md-right']) }}
    <div class="col-md-6">
        {{ 
            Form::textarea(
                'description',
                old('description'),
                ['class' => 'form-control' . ( $errors->has('description') ? ' is-invalid' : '')]
            ) 
        }}

        @error('description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
           </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label('status_id', __('Task Status'), ['class' => 'col-md-4 col-form-label text-md-right']) }}
    <div class="col-md-6">
        {{ 
            Form::select(
                'status_id', 
                $statuses, 
                $task->taskStatus->id ?? null, 
                ['class' => 'form-control' . ( $errors->has('status_id') ? ' is-invalid' : ''), 'placeholder' => __('Select satus')]
            ) 
        }}

        @error('status_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
           </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label('assigned_to_id', __('Assignee'), ['class' => 'col-md-4 col-form-label text-md-right']) }}
    <div class="col-md-6">
        {{ 
            Form::select(
                'assigned_to_id', 
                $assignees, 
                $task->assignedTo->id ?? null, 
                ['class' => 'form-control' . ( $errors->has('assigned_to_id') ? ' is-invalid' : ''), 'placeholder' => __('Select assignee')]
            ) 
        }}

        @error('assigned_to_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
           </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label('tag', __('Tags'), ['class' => 'col-md-4 col-form-label text-md-right']) }}
    <div class="col-md-6">
        {{ 
            Form::text(
                'tag', 
                $tag ?? old('tag'), 
                ['class' => 'form-control'. ( $errors->has('tag') ? ' is-invalid' : ''), 'placeholder' => __('Enter tags separated by commas')]
            ) 
        }}

        @error('tag')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
        @enderror
    </div>
</div>

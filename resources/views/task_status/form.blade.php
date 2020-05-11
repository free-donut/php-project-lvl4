
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
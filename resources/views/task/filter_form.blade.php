{{Form::open(['url' => route('tasks.index'), 'method' => 'GET', 'class' => 'form-inline'])}}
    {{ Form::select('filter[status_id]', $statuses, null, ['class' => 'form-control mr-2', 'placeholder' => __('Status')]) }}
    {{ Form::select('filter[tag_id]', $tags, null, ['class' => 'form-control mr-2', 'placeholder' => __('Tag')]) }}
    {{ Form::select('filter[creator_id]', $creators, null, ['class' => 'form-control mr-2', 'placeholder' => __('Creator')]) }}
    {{ Form::select('filter[assigned_to_id]', $creators, null, ['class' => 'form-control mr-2', 'placeholder' => __('Assignee')]) }}
    {{Form::submit(__('Apply'), ['class' => 'btn btn-outline-primary ml-auto mr-2'])}}
    {{Form::reset(__('Reset'), ['class' => 'btn btn-outline-primary ml-auto mr-2'])}}
{{Form::close()}}

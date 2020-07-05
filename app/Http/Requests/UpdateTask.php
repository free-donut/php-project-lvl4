<?php

namespace App\Http\Requests;

use App\Http\Requests\StoreTask;
use Illuminate\Validation\Rule;

class UpdateTask extends StoreTask
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => 'required|unique:tasks,name,' . $this->route('task'),

        ]);
    }
}

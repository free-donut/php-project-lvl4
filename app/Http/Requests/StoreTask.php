<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTask extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
        //return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:tasks,name,',
            'description' => 'max:1000',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'required|exists:users,id',
            'tagData' => 'max:255',
        ];
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //

    protected $fillable = [
        'name', 'status_id', 'assigned_to_id', 'description',
    ];

    public function taskStatus()
    {
        return $this->belongsTo('App\TaskStatus', 'status_id');
    }

	public function assignedTo()
    {
        return $this->belongsTo('App\User', 'assigned_to_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'tag_task');
    }
}

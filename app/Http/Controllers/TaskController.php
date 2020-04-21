<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\TaskStatus;
use App\User;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy('created_at', 'desc')->paginate(5);

        return view('task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
            return redirect()->route('main');
        }
        $taskStatuses = TaskStatus::all();
        $assignees = User::all();
        return view('task.create', compact('taskStatuses', 'assignees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
            return redirect()->route('main');
        }

        $params = $this->validate($request, [
            'name' => 'required|unique:tasks,name,',
            'description' => 'max:1000',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'required|exists:users,id',
        ]);

        $creator = Auth::user();
        $task = $creator->creatorTasks()->make($params);
        $task->save();
        flash(__('Your task has been saved.'))->success();
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
            return redirect()->route('main');
        }

        $taskStatuses = TaskStatus::all();
        $assignees = User::all();
        $task = Task::findOrFail($id);
        return view('task.edit', compact('task', 'taskStatuses', 'assignees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
            return redirect()->route('main');
        }
        $task = Task::findOrFail($id);

        $data = $this->validate($request, [
            'name' => 'required|unique:tasks,name,' . $id,
            'description' => 'required|max:1000',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'required|exists:users,id',
        ]);

        $task->fill($data);
        $task->save();
        flash(__('Your task status has been updated.'))->success();

        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
        }
        $task = Task::find($id);
        if ($task) {
            $task->delete();
            flash(__('Task has been deleted.'))->success();
        } else {
            flash(__('Task doesn\'t exist!'))->error();
        }
        return redirect()->route('tasks.index');
    }
}

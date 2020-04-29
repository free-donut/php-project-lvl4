<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TaskStatus;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taskStatuses = TaskStatus::orderBy('created_at', 'desc')->paginate(10);
        return view('task_status.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check()) {
            flash(__('messages.not_logged'))->error();
            return redirect()->route('main');
        }
        return view('task_status.create');
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $this->validate($request, [
            'name' => 'required|unique:task_statuses,name,',
        ]);

        $taskStatus = new TaskStatus();

        $taskStatus->name = $request->name;

        $taskStatus->save();
        flash(__('messages.saved', ['name' => 'Task Status']))->success();
        return redirect()->route('task_statuses.index');
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
            flash(__('messages.not_logged'))->error();
            return redirect()->route('main');
        }
        
        $taskStatus = TaskStatus::findOrFail($id);
        return view('task_status.edit', compact('taskStatus'));
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
        $taskStatus = TaskStatus::findOrFail($id);

        $data = $this->validate($request, [
            'name' => 'required|unique:task_statuses,name,' . $id,
        ]);
        $taskStatus->name = $request->name;
        $taskStatus->save();
        flash(__('messages.updated', ['name' => 'Task Status']))->success();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        $taskStatus->delete();
        flash(__('messages.deleled', ['name' => 'Task Status']))->success();
        return redirect()->route('task_statuses.index');
    }
}

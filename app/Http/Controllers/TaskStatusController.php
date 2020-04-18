<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $taskStatuses = TaskStatus::orderBy('created_at', 'desc')->paginate(5);
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
            flash(__('Please log in or register.'))->error();
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
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
            return redirect()->route('main');
        }

        $name = $this->validate($request, [
            'name' => 'required|unique:task_statuses,name,',
        ]);

        $taskStatus = new TaskStatus();

        $taskStatus->name = $request->name;

        $taskStatus->save();
        flash(__('Your task status has been saved.'))->success();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
            return redirect()->route('main');
        }

        $taskStatus = TaskStatus::findOrFail($id);

        $data = $this->validate($request, [
            'name' => 'required|unique:task_statuses,name,' . $id,
        ]);
        $taskStatus->name = $request->name;
        $taskStatus->save();
        flash(__('Your task status has been updated.'))->success();
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
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
        }
        $taskStatus = TaskStatus::find($id);
        if ($taskStatus) {
            $taskStatus->delete();
            flash(__('Task Status has been deleted.'))->success();
        } else {
            flash(__('Task Status doesn\'t exist!'))->error();
        }
        return redirect()->route('task_statuses.index');
    }
}

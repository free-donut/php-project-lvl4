<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Tag;
use App\TaskStatus;
use App\User;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->filter) {
            $filter = $request->filter;
            $tasks = Task::query();

            if ($filter['status_id']) {
                $tasks->where('status_id', $filter['status_id']);
            } elseif ($filter['creator_id']) {
                $tasks->orWhere('creator_id', $filter['creator_id']);
            } elseif ($filter['assigned_to_id']) {
                $tasks->orWhere('assigned_to_id', $filter['assigned_to_id']);
            }

            $tasks = $tasks->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $tasks = Task::orderBy('created_at', 'desc')->paginate(10);
        }

        $statuses = TaskStatus::all();
        $creators = User::orderBy('name', 'asc')->get();
        $assignees = User::orderBy('name', 'asc')->get();
        return view('task.index', compact('tasks', 'statuses', 'creators', 'assignees'));
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
        $tags = Tag::all();
        $statuses = TaskStatus::all();
        $assignees = User::all();
        $defaultStatus = TaskStatus::where('name', '=', 'new')->first();
        return view('task.create', compact('statuses', 'assignees', 'tags', 'defaultStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedParams = $this->validate($request, [
            'name' => 'required|unique:tasks,name,',
            'description' => 'max:1000',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'required|exists:users,id',
        ]);

        $creator = Auth::user();
        $task = $creator->creatorTasks()->make($validatedParams);
        $task->save();
        if ($request->tags) {
            $validatedTags = $this->validate($request, [
                'tags.*' => 'exists:tags,id',
            ]);
            $task->tags()->sync($validatedTags['tags']);
        }
        if ($request->newTag) {
            $newTag = new Tag();
            $newTag->name = $request->newTag;
            $task->tags()->attach($newTag);
        }

        flash(__('messages.saved', ['name' => 'Task']))->success();

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
            flash(__('messages.not_logged'))->error();
            return redirect()->route('main');
        }
        $tags = Tag::all();
        $statuses = TaskStatus::all();
        $assignees = User::all();
        $task = Task::findOrFail($id);
        $selectedTags = $task->tags()->get()->pluck('id')->toArray();
        return view('task.edit', compact('task', 'statuses', 'assignees', 'tags', 'selectedTags'));
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
        $task = Task::findOrFail($id);

        $data = $this->validate($request, [
            'name' => 'required|unique:tasks,name,' . $id,
            'description' => 'required|max:1000',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'required|exists:users,id',
        ]);

        $task->fill($data);
        $task->save();
        if ($request->tags) {
            $validatedTags = $this->validate($request, [
                'tags.*' => 'exists:tags,id',
            ]);
            $task->tags()->sync($validatedTags['tags']);
        }
        if ($request->newTag) {
            $newTag = new Tag();
            $newTag->name = $request->newTag;
            $task->tags()->attach($newTag);
        }
        flash(__('messages.updated', ['name' => 'Task']))->success();

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
        $task = Task::findOrFail($id);

        if ($task->creator->id === Auth::id()) {
            $task->delete();
            flash(__('messages.deleled', ['name' => 'Task']))->success();
        } else {
            flash(__('messages.denied.'))->error();
        }

        return redirect()->route('tasks.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Tag;
use App\TaskStatus;
use App\User;
use Illuminate\Database\Eloquent\Builder;

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
            $validatedParams = $this->validate($request, [
                'filter.status_id' => 'nullable|exists:task_statuses,id',
                'filter.creator_id' => 'nullable|exists:users,id',
                'filter.assigned_to_id' => 'nullable|exists:users,id',
                'filter.tag_id' => 'nullable|exists:tags,id',
            ]);

            $filterParams = $validatedParams['filter'];
            $tasks = Task::query();

            if ($filterParams['status_id']) {
                $tasks->where('status_id', $filterParams['status_id']);
            } elseif ($filterParams['creator_id']) {
                $tasks->orWhere('creator_id', $filterParams['creator_id']);
            } elseif ($filterParams['assigned_to_id']) {
                $tasks->orWhere('assigned_to_id', $filterParams['assigned_to_id']);
            } elseif ($filterParams['tag_id']) {
                $tasks->orWhereHas('tags', function (Builder $query) use ($filterParams) {
                    $query->where('tag_id', $filterParams['tag_id']);
                });
            }

            $tasks = $tasks->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $tasks = Task::orderBy('created_at', 'desc')->paginate(10);
        }

        $tags = Tag::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $statuses = TaskStatus::pluck('name', 'id')->toArray();
        $assignees = User::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $creators = User::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return view('task.index', compact('tasks', 'statuses', 'creators', 'assignees', 'tags'));
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

        $task = new Task();
        $statuses = TaskStatus::pluck('name', 'id')->toArray();
        $assignees = User::orderBy('name', 'asc')->pluck('name', 'id')->toArray();

        return view('task.create', compact('task', 'statuses', 'assignees'));
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
            'tagData' => 'max:255',
        ]);

        $creator = Auth::user();
        $task = $creator->creatorTasks()->make($validatedParams);
        $task->save();

        if ($request->tagData) {
            $tags = array_map('trim', explode(',', $request->tagData));

            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $task->tags()->attach($tag);
            }
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
        $task = Task::findOrFail($id);
        $statuses = TaskStatus::pluck('name', 'id')->toArray();
        $assignees = User::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $tags = $task->tags()->get()->pluck('name')->toArray();
        $tagData = implode(', ', $tags);
        return view('task.edit', compact('task', 'statuses', 'assignees', 'tagData'));
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

        $validatedParams = $this->validate($request, [
            'name' => 'required|unique:tasks,name,' . $id,
            'description' => 'max:1000',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'required|exists:users,id',
            'tagData' => 'max:255',
        ]);

        $task->fill($validatedParams);
        $task->save();
        if ($request->tagData) {
            $tags = array_map('trim', explode(',', $request->tagData));

            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $task->tags()->attach($tag);
            }
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

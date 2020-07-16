<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTask;
use App\Http\Requests\UpdateTask;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\Tag;
use App\TaskStatus;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'edit']);
    }

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

            $tasks = QueryBuilder::for(Task::class)
                ->allowedFilters(['status_id', 'creator_id', 'assigned_to_id', 'tag_id'])
                ->paginate(10)
                ->appends(request()->query());
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
    public function store(StoreTask $request)
    {
        $validatedParams = $request->validated();

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
    public function update(UpdateTask $request, $id)
    {
        $task = Task::findOrFail($id);
        $validatedParams = $request->validated();

        $task->fill($validatedParams);
        $task->save();
        
        $tags = [];
        if ($request->tagData) {
            $tags = array_map('trim', explode(',', $request->tagData));

            foreach ($tags as $tagName) {
                $tags[] = Tag::firstOrCreate(['name' => $tagName])->id;
            }
        }
        $task->tags()->sync($tags);

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
        $this->authorize('delete', $task);
        $task->delete();
        flash(__('messages.deleled', ['name' => 'Task']))->success();

        return redirect()->route('tasks.index');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Task;
use App\User;

class TaskTest extends TestCase
{
    public function testIndex()
    {
        $this->seed();

        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('tasks.create'));
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $this->seed();
        $params = factory(Task::class)->make()->toArray();
        $user = User::find($params['creator_id']);

        $response = $this->actingAs($user)->post(route('tasks.store'), $params);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', $params);
    }

    public function testShow()
    {
        $this->seed();
        $task = factory(Task::class)->create();

        $response = $this->get(route('tasks.show', $task));
        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $this->seed();
        $task = factory(Task::class)->create();

        $response = $this->actingAs($task->creator)->get(route('tasks.edit', $task));
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $this->seed();
        $task = factory(Task::class)->create();
        $params = factory(Task::class)->make(['creator_id' => $task->creator->id])->toArray();

        $response = $this->actingAs($task->creator)->patch(route('tasks.update', $task), $params);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', $params);
    }

    public function testDestroy()
    {
        $this->seed();
        $task = factory(Task::class)->create();
        
        $response = $this->actingAs($task->creator)->delete(route('tasks.destroy', $task));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('tasks.index'));
        
        $this->assertSoftDeleted($task);
    }
}

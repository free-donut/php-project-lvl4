<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\TaskStatus;
use App\User;

class TaskStatusTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testIndex()
    {
        $this->seed();

        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('task_statuses.create'));
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $user = factory(User::class)->create();
        $params = ['name' => 'Gandalf'];

        $response = $this->actingAs($user)->post(route('task_statuses.store'), $params);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', $params);
    }


    public function testEdit()
    {
        $user = factory(User::class)->create();
        $taskStatus = factory(TaskStatus::class)->create();

        $response = $this->actingAs($user)->get(route('task_statuses.edit', $taskStatus));
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();
        $taskStatus = factory(TaskStatus::class)->create();
        $params = ['name' => 'Draco'];

        $response = $this->actingAs($user)->patch(route('task_statuses.update', $taskStatus), $params);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('task_statuses.index'));

        $this->assertDatabaseHas('task_statuses', $params);
    }

    public function testDestroy()
    {
        $user = factory(User::class)->create();
        $taskStatus = factory(TaskStatus::class)->create();

        $response = $this->actingAs($user)->delete(route('task_statuses.destroy', $taskStatus));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('task_statuses.index'));

        $this->assertDeleted($taskStatus);
    }
}

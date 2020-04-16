<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->seed();
        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $user = factory(User::class)->create();

        $response = $this->get(route('users.show', ['user' => $user->id]));

        $response->assertStatus(200);
    }

    public function testDestroy()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->delete(route('users.destroy', ['user' => $user->id]));
        $this->assertDeleted($user);
        //$response->assertStatus(200);
    }
    
    public function testEdit()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

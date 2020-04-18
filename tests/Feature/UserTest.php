<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;

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
        $response->assertStatus(302);
        $this->assertDeleted($user);
        //$response->assertStatus(200);
    }

    public function testEdit()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('users.edit', $user));

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $password = '30013001';
        $hashedPassword = Hash::make($password);

        $user = factory(User::class)->create([
            'password' => $hashedPassword
        ]);

        $params = [
            'name' => 'Ron',
            'email' => 'ron@weasley.com',
            'password' => $password
        ];

        $response = $this->actingAs($user)->patch(route('users.update', $user), $params);
        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'Ron',
            'email' => 'ron@weasley.com',
        ]);
    }

    public function testUpdateWithValidationErrors()
    {
        $password = '30013001';
        $hashedPassword = Hash::make($password);

        $user = factory(User::class)->create([
            'password' => $hashedPassword
        ]);

        $params = [
            'name' => 'Hermione',
            'email' => 'alohomora',
            'password' => $password
        ];

        $response = $this->actingAs($user)->patch(route('users.update', $user), $params);
        $response->assertStatus(302);

        $this->assertDatabaseMissing('users', [
            'name' => 'Hermione',
            'email' => 'alohomora',
        ]);
    }
}

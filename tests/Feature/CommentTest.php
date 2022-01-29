<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password'
        ])->assertJson([
            'access_token' => true,
        ]);

//        $response1 = $this->withHeaders([
//            'Authorization' => 'Bearer ' .$this->assertTrue($response['access_token']),
//        ])->get('/api/v1/badges');


        $response = $this->get('/users/1/achievements');

        $response->assertStatus(200);
    }
}

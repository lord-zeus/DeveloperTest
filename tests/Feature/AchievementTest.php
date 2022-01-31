<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_achievement()
    {

        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ])->decodeResponseJson();
        $access = json_decode($response->json);
        $user_achievement = $this->withHeaders([
            'Authorization' => 'Bearer ' . $access->access_token
        ])->postJson('/api/v1/achievements', [
            'name' => Str::random(20),
            'number' => 30,
            'type' => 'comment'
        ]);

        $user_achievement->assertStatus(200);
    }
}

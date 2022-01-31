<?php

namespace Tests\Feature;

use App\Models\Comment;
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
    public function test_comment()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ])->decodeResponseJson();
        $access = json_decode($response->json);
        $response = $this->get('/users/1/achievements');

        $response->assertStatus(200);

        $comments = Comment::factory(5)->make([
            'user_id' => $user->id
        ]);
        foreach ($comments as $comment){
            $user_comment = $this->withHeaders([
                'Authorization' => 'Bearer ' . $access->access_token
            ])->postJson('/api/v1/comments', [
                'user_id' => $user->id,
                'body' => $comment->body
            ]);

            $user_comment
                ->assertStatus(200)
                ->assertJson([
                    'code' => true,
                ])
                ->assertJsonPath('data.body', $comment->body);

        }

        $user_achievements = $this->getJson("/users/{$user->id}/achievements");
        $user_achievements ->assertStatus(200)
            ->assertJson(['code'=>true])
            ->assertJsonPath('data.current_badge', "Beginner");
//        $user_achievements_decode = $user_achievements->decodeResponseJson();
    }
}

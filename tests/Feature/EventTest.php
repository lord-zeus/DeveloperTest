<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_achievement_and_badge_unlocked()
    {
        Event::fake();
        $user = User::factory()->create();
        event(new AchievementUnlocked('new_achivement', $user));
        event(new BadgeUnlocked('new_badge', $user));
        Event::assertDispatched(AchievementUnlocked::class);
        Event::assertDispatched(BadgeUnlocked::class);
    }
}

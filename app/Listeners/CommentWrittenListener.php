<?php

namespace App\Listeners;

use App\Models\Achievement;
use App\Models\Badge;
use App\Traits\AchievementTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CommentWritten;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentWrittenListener
{
    use AchievementTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        $user = Auth::user();
        $comments = count($user->comments);
        $achievement = Achievement::where('type', 'comment')
            ->where('number', $comments)
            ->first();
        if(!empty($achievement)){
            DB::table('achievement_user')->insert([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id
            ]);
            $badges = $user->badges;
            if(count($badges) == 0){
                $badge = Badge::where('number_achievements', 0)->first();
                if(!empty($badge)){
                    DB::table('badge_user')->insert([
                        'badge_id' => $badge->id,
                        'user_id' => $user->id
                    ]);
                }
            }
            $user_achievement = $user->achievements;
            $badge = Badge::where('number_achievements', count($user_achievement))->first();
            if(!empty($badge)){
                DB::table('badge_user')->insert([
                    'badge_id' => $badge->id,
                    'user_id' => $user->id
                ]);
            }

        }

    }
}

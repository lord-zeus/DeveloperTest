<?php
namespace App\Traits;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Badge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait BadgeTrait {

    public function achievements($achievement){
        $user = Auth::user();
        $user_achievement = $user->achievements;
        $achieve_ids = [];
        foreach ($user_achievement as $achieve){
            array_push($achieve_ids, $achieve->id);
        }
        if(in_array($achievement->id, $achieve_ids)){
            Log::debug('achievemen alrady ne');
            return;
        }
        DB::table('achievement_user')->insert([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id
        ]);
        AchievementUnlocked::dispatch($achievement->name, $user);
        $badges = $user->badges;
        if(count($badges) == 0){
            $badge = Badge::where('number_achievements', 0)->first();
            if(!empty($badge)){
                DB::table('badge_user')->insert([
                    'badge_id' => $badge->id,
                    'user_id' => $user->id
                ]);
                BadgeUnlocked::dispatch($badge->name, $user);
            }
        }
        $badge = Badge::where('number_achievements', count($user_achievement))->first();
        if(!empty($badge)){
            DB::table('badge_user')->insert([
                'badge_id' => $badge->id,
                'user_id' => $user->id
            ]);
            BadgeUnlocked::dispatch($badge->name, $user);
        }
    }
}

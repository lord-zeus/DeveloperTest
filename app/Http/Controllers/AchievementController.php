<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AchievementController extends Controller
{

    use APIResponse;
    public function index(){
        $achievements = Achievement::all();
        return $this->successResponse($achievements);

        return response()->json([
            'unlocked_achievements' => [],
            'next_available_achievements' => [],
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:achievements',
            'number' => 'required',
            'type' => 'required|in:comment,lesson',
        ]);
        $achievement = Achievement::create($request->all());
        return $this->successResponse($achievement);
    }

    public function unlockedAchievements($user_id){
        $user = User::findOrFail($user_id);
        $achievements = $user->achievements->sortBy('number');
        $comment =  $achievements->where('type', 'comment')->last();
        $lesson = $achievements->where('type', 'lesson')->last();
        if(empty($comment)){
            $all_achievement_comment = Achievement::where('type', 'comment')->orderBy('number')->first();
        }
        else
            $all_achievement_comment = Achievement::where('type', 'comment')->where('number', '>', $comment->number)->orderBy('number')->first();

        if(empty($lesson)){
            $all_achievement_lesson = Achievement::where('type', 'lesson')->orderBy('number')->first();
        }
        else
            $all_achievement_lesson = Achievement::where('type', 'lesson')->where('number', '>', $lesson->number)->orderBy('number')->first();

        $user_badge = $user->badges->sortBy('number_achievements')->last();
        if(empty($user_badge)){
            $user_badge = Badge::orderBy('number_achievements')->first();
            Log::debug('new user');
            Log::debug($user_badge);
            DB::table('badge_user')->insert([
                'badge_id' => $user_badge->id,
                'user_id' => $user->id
            ]);
        }
        $next = Badge::where('number_achievements', '>', $user_badge->number_achievements)->orderBy('number_achievements')->first();
        $final = [
            'unlocked_achievements' => $achievements->pluck('name'),
            'next_available_achievements' => [$all_achievement_lesson->name, $all_achievement_comment->name],
            'current_badge' => $user_badge->name,
            'next_badge' => $next->name,
            'remaining_to_unlock_next_badge' =>( (int)$next->number_achievements - count($achievements))
        ];
        return $this->successResponse($final);
    }
}

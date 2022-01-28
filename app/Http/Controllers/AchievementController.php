<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Traits\APIResponse;
use Illuminate\Http\Request;

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
}

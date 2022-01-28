<?php

namespace App\Http\Controllers;

use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Lesson;
use App\Traits\APIResponse;
use App\Traits\BadgeTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    use APIResponse;
    use BadgeTrait;
    public function index(){
        $lesson = Lesson::all();
        return $this->successResponse($lesson);
    }

    public function store(Request $request){
        $this->validate($request, [
            'title' => 'required|unique:lessons',
        ]);
        $lesson = Lesson::create($request->all());
        return $this->successResponse($lesson);
    }

    public function assignLesson($lesson_id, $user_id){
        $lesson = Lesson::findOrFail($lesson_id);
        $user_lesson = DB::table('lesson_user')->where('user_id', $user_id)->where('lesson_id', $lesson_id)->first();
        if(empty($user_lesson)){
            DB::table('lesson_user')->insert([
                'user_id' => $user_id,
                'lesson_id' => $lesson_id
            ]);
        }
        return $this->successResponse($lesson);
    }

    public function watchLesson($lesson_id){
        $user = Auth::user();
        $lesson = Lesson::findOrFail($lesson_id);
        $user_lesson = DB::table('lesson_user')->where('user_id', $user->id)->where('lesson_id', $lesson_id)->first();
        if(empty($user_lesson)){
            return $this->errorResponse('Sorry you do not yet have access to that lesson', Response::HTTP_UNAUTHORIZED);
        }
        if($user_lesson->watched == 0){
            DB::table('lesson_user')->where('user_id', $user->id)
                ->where('lesson_id', $lesson_id)
                ->update(['watched'=> 1]);

            $lesson_watched = DB::table('lesson_user')->where('user_id', $user->id)
                ->where('lesson_id', $lesson_id)
                ->where('watched', 1)
                ->get();
            $achievement = Achievement::where('type', 'lesson')
                ->where('number', count($lesson_watched))
                ->first();
            if(!empty($achievement)){
                $this->achievements($achievement);
            }
        }
        return $this->successResponse($lesson);
    }
}


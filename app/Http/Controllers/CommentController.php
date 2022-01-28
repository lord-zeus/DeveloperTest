<?php

namespace App\Http\Controllers;

use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Comment;
use App\Traits\AchievementTrait;
use App\Traits\APIResponse;
use App\Traits\BadgeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    use APIResponse;
    use BadgeTrait;
    public function index(){
        $comments = Comment::all();
        return $this->successResponse($comments);
    }

    public function store(Request $request){
        $this->validate($request, [
            'body' => 'required',
        ]);
        $user = Auth::user();
        $request->merge(['user_id' => $user->id]);
        $comment = Comment::create($request->all());
        $comments = count($user->comments);
        $achievement = Achievement::where('type', 'comment')
            ->where('number', $comments)
            ->first();
        if(!empty($achievement)){
            $this->achievements($achievement);
        }
        return $this->successResponse($comment);
    }
}

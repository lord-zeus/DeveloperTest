<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use APIResponse;
    public function index(){
        $comments = Comment::all();
        return $this->successResponse($comments);
    }

    public function store(Request $request){
        $this->validate($request, [
            'body' => 'required',
        ]);
        $user_id = Auth::id();
        $request->merge(['user_id' => $user_id]);
        $comment = Comment::create($request->all());
        return $this->successResponse($comment);
    }
}

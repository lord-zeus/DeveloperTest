<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Traits\APIResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    use APIResponse;
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


}


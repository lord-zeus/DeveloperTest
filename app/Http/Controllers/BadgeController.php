<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Traits\APIResponse;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    use APIResponse;
    public function index(){
        $badges = Badge::all();
        return $this->successResponse($badges);
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:badges',
            'number_achievements' => 'required|unique:badges',
        ]);
        $badge = Badge::create($request->all());
        return $this->successResponse($badge);
    }
}

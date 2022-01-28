<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    use APIResponse;
    public function register(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:255|min:6',
            'email' => 'required|unique:users|string|email|max:255',
            'password' => 'required|confirmed|string|min:4',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return $this->login($request);
    }

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();


        if(empty($user)){
            return $this->errorResponse('Authentication Failed', Response::HTTP_UNAUTHORIZED);
        }


        $user = $user->makeVisible(['password']);
        if(Hash::check($request->password, $user->password)){
            $params = [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ];
            $request->request->add($params);
            $proxy = Request::create('oauth/token', 'POST');
            return Route::dispatch($proxy);
        }
        else {
            return $this->errorResponse('UNAUTHENTICATED', Response::HTTP_UNAUTHORIZED);
        }
    }

}

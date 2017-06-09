<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UserController extends Controller
{
    public function signUp(Request $request){

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if(!$user->save()){

            return response()->json(['message' => 'server error'],501);
        }

        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);

        return response()->json(['message'=>'user created successfully', 'token' => $token], 200);
    }

    public function signIn(Request $request){

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function logOut(){

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message'=>'successfully logeout']);
    }

    public function showProfile(User $user){

        $response['user'] = $user;
        $response['pictures'] = $user->pictures->all();

        return response()->json($response,200);
    }

    public function getWatchlist(User $user){

        $response = $user->watchlist;
        return response()->json($response,200);

    }

    public function followUser(User $user){

        $authUser = JWTAuth::parseToken()->authenticate();

        $user->followers()->attach($authUser->id);

        return response()->json(['message'=>'followee was added'],200);
    }

    public function followUserCancel(User $user){

        //get auth user $authUser

        $user->followers([2])->detach();

        return response()->json(['message'=>'followee was cancel'],200);
    }

    public function getFollowers(User $user){

        $response = $user->followers()->get();

        return response()->json($response,200);
    }

    public function getFollowee(User $user){

        $response = $user->followees()->get();

        return response()->json($response,200);
    }

}

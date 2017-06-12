<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class UserController extends Controller
{

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

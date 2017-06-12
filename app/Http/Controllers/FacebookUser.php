<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use JWTAuth;


class FacebookUser extends Controller
{
    public function store(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){

        try {
            $token = $fb->getJavaScriptHelper()->getAccessToken();
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // Failed to obtain access token
            dd($e->getMessage());
        }

        // Access token will be null if the user denied the request
        // or if someone just hit this URL outside of the OAuth flow.
        if (! $token) {
            // Get the redirect helper
            $helper = $fb->getRedirectLoginHelper();

            if (! $helper->getError()) {
                abort(403, 'Unauthorized action.');
            }

            // User denied the request
            dd(
                $helper->getError(),
                $helper->getErrorCode(),
                $helper->getErrorReason(),
                $helper->getErrorDescription()
            );
        }

        if (! $token->isLongLived()) {
            // OAuth 2.0 client handler
            $oauth_client = $fb->getOAuth2Client();

            // Extend the access token.
            try {
                $token = $oauth_client->getLongLivedAccessToken($token);
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                dd($e->getMessage());
            }
        }

        $fb->setDefaultAccessToken($token);

        // Get basic info on the user from Facebook.
        try {
            $response = $fb->get('/me?fields=id,name,email');
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
        }

        // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
        $facebook_user = $response->getGraphUser();

        $facebook_user = $facebook_user->all();

        $fbUser['facebook_id'] = $facebook_user['id'];
        $fbUser['name'] = $facebook_user['name'];
        $fbUser['password'] = bcrypt(str_random(7));

        // Create the user if it does not exist or update the existing entry.
        // This will only work if you've added the SyncableGraphNodeTrait to your User model.

        if(isset($facebook_user['email'])){

            $fbUser['email'] = $facebook_user['email'];
            $user = User::whereEmail([$facebook_user['email']])->first();

            if(isset($user)){

                if($user->facebook_id == $facebook_user['id']){

                    $user_token = JWTAuth::fromUser($user);
                    return response()->json(['user'=>$user,'token'=>$user_token]);
                }
                else{

                    $user = $user->update($facebook_user['id']);
                    $user_token = JWTAuth::fromUser($user);
                    return response()->json(['user'=>$user,'token'=>$user_token]);
                }
            }
            else{

                $user = User::create($fbUser);
                $user_token = JWTAuth::fromUser($user);
                return response()->json(['user'=>$user,'token'=>$user_token]);
            }

        }
        else{

            $user = User::where('facebook_id','=', $facebook_user['id'])->first();

            if(isset($user)){

                $user_token = JWTAuth::fromUser($user);
                return response()->json(['user'=>$user,'token'=>$user_token]);
            }
            else{

                $user = User::create($fbUser);
                $user_token = JWTAuth::fromUser($user);
                return response()->json(['user'=>$user,'token'=>$user_token]);
            }


        }

        //return redirect('/')->with('message', 'Successfully logged in with Facebook');

    }
}

<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('/picture','PictureController');

Route::post('/picture/{picture}/watchlist','PictureController@addToWatchlist');

Route::delete('/picture/{picture}/watchlist','PictureController@removeFromWatchlist');

Route::post('/signup','UserController@signUp');

Route::post('/signin','UserController@signIn');

Route::post('/logout','UserController@logOut');

Route::get('/user/{user}','UserController@showProfile');

Route::get('/user/{user}/watchlist','UserController@getWatchlist')->middleware('jwt.auth');

Route::post('/follow/{user}/','UserController@followUser')->middleware('jwt.auth');

Route::delete('/follow/{user}/','UserController@followUserCancel')->middleware('jwt.auth');

Route::get('/user/{user}/followers', 'UserController@getFollowers')->middleware('jwt.auth');

Route::get('/user/{user}/followees','UserController@getFollowee')->middleware('jwt.auth');
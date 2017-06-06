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

Route::post('/signup','UserController@signUp');

Route::post('/signin','UserController@signIn');

Route::get('/user/{user}','UserController@showProfile');

Route::resource('/user/{user}/watchlist','WatchlistController');
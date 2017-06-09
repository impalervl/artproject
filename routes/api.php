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


Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...

Route::post('/register', 'Auth\RegisterController@register');
Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');

// Password Reset Routes...

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::post('/signup','UserController@signUp');

Route::post('/signin','UserController@signIn');

Route::post('/logout','UserController@logOut');

Route::get('/user/{user}','UserController@showProfile');

Route::get('/user/{user}/watchlist','UserController@getWatchlist')->middleware('jwt.auth');

Route::post('/follow/{user}/','UserController@followUser')->middleware('jwt.auth');

Route::delete('/follow/{user}/','UserController@followUserCancel')->middleware('jwt.auth');

Route::get('/user/{user}/followers', 'UserController@getFollowers')->middleware('jwt.auth');

Route::get('/user/{user}/followees','UserController@getFollowee')->middleware('jwt.auth');
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
Route::post('/register', 'Auth\RegisterController@register');
Route::get('register/verifyemail/{token}', 'Auth\RegisterController@verify');

Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::post('/facebook/login', 'FacebookUser@store');

Route::get('/countries','CityCountryController@getCountries');

Route::get('/cities','CityCountryController@getCities');

Route::get('/user/{user}','UserController@showProfile');

Route::get('/user/{user}/watchlist','UserController@getWatchlist')->middleware('jwt.auth');

Route::post('/follow/{user}/','UserController@followUser')->middleware('jwt.auth');

Route::delete('/follow/{user}/','UserController@followUserCancel')->middleware('jwt.auth');

Route::get('/user/{user}/followers', 'UserController@getFollowers')->middleware('jwt.auth');

Route::get('/user/{user}/followees','UserController@getFollowee')->middleware('jwt.auth');

Route::resource('/picture','PictureController');

Route::post('/picture/{picture}/watchlist','PictureController@addToWatchlist');

Route::delete('/picture/{picture}/watchlist','PictureController@removeFromWatchlist');
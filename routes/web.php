<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/plans', 'BrainTreeController@index');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/plan/{plan}', 'BrainTreeController@show');

    Route::get('/braintree/token', 'BrainTreeController@token');

    Route::post('/subscribe', 'SubscriptionsController@store');

    Route::get('/customer', 'SubscriptionsController@nextBilling');
});

Route::post(
    'braintree/webhook',
    '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);

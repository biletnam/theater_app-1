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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// users list
Route::get('users', 'UserController@index');
// user
Route::get('user/{id}', 'UserController@show');
// new user
Route::post('user', 'UserController@store');
// update user
Route::put('user', 'UserController@store');
// delete user
Route::delete('user/{id}', 'UserController@destroy');

// reservations list
Route::get('reservations', 'ReservationController@index');
// reservation
Route::get('reservation/{id}', 'ReservationController@show');
// new reservation
Route::post('reservation', 'ReservationController@store');
// update reservation
Route::put('reservation', 'ReservationController@store');
// delete reservation
Route::put('reservation/{id}', 'ReservationController@destroy');

Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');


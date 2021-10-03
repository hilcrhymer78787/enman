<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// 完成
Route::post('/task/create', 'TaskController@create');
Route::get('/task/read', 'TaskController@read');
Route::delete('/task/delete', 'TaskController@delete');

// 完成
Route::get('/read', 'UserController@read');
Route::post('/user/create', 'UserController@create');
Route::get('/user/read', 'UserController@read');
Route::put('/user/update/room_id', 'UserController@updateRoomId');
Route::delete('/user/delete', 'UserController@delete');

// 完成
Route::post('/work/create', 'WorkController@create');
Route::get('/work/read', 'WorkController@read');
Route::delete('/work/delete', 'WorkController@delete');

// 完成
Route::get('/room/read', 'RoomController@read');
Route::post('/room/create', 'RoomController@create');


Route::post('/invitation/create', 'InvitationController@create');
Route::get('/invitation/read', 'InvitationController@read');
Route::put('/invitation/update', 'InvitationController@update');
Route::delete('/invitation/delete', 'InvitationController@delete');

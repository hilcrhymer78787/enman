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

Route::get('/test', 'UserController@test');

Route::post('/task/create', 'TaskController@create');
Route::get('/task/read', 'TaskController@read');
Route::get('/task/show', 'TaskController@show');
Route::delete('/task/delete', 'TaskController@delete');


Route::get('/user/login_info', 'UserController@login_info');
Route::post('/user/create', 'UserController@create');
Route::put('/user/update', 'UserController@update');

Route::get('/user/read', 'UserController@read');

Route::get('/room/read', 'RoomController@read');

Route::get('/work/read', 'WorkController@read');
Route::post('/work/create', 'WorkController@create');
Route::delete('/work/delete', 'WorkController@delete');

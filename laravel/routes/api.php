<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckToken;

// コントローラーの use 宣言
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\InvitationController;

// 認証付きルート（例：Laravel SanctumやPassportを使っている場合）
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 認証不要ルート
Route::get('/user/test_authentication', [UserController::class, 'test_authentication']); // 型付け終了
Route::post('/user/basic_authentication', [UserController::class, 'basic_authentication']); // 型付け終了
Route::get('/user/bearer_authentication', [UserController::class, 'bearer_authentication']); // 型付け終了
Route::post('/user/create', [UserController::class, 'create']); // 型付け終了

// CheckTokenミドルウェアを使うグループルート
Route::middleware([CheckToken::class])->group(function () {
    Route::post('/task/sortset', [TaskController::class, 'sortset']); // 型付け終了
    Route::post('/task/create', [TaskController::class, 'create']); // 型付け終了
    Route::get('/task/read', [TaskController::class, 'read']); // 型付け終了
    Route::delete('/task/delete', [TaskController::class, 'delete']); // 型付け終了

    Route::put('/user/update/room_id', [UserController::class, 'updateRoomId']); // 型付け終了
    Route::delete('/user/delete', [UserController::class, 'delete']); // 型付け終了

    Route::post('/work/create', [WorkController::class, 'create']); // 型付け終了
    Route::get('/work/read/calendar', [WorkController::class, 'read_calendar']); // 型付け終了
    Route::post('/work/read/analytics', [WorkController::class, 'read_analytics']); // 型付け終了
    Route::delete('/work/delete', [WorkController::class, 'delete']); // 型付け終了

    Route::post('/room/create', [RoomController::class, 'create']); // 型付け終了

    Route::post('/invitation/create', [InvitationController::class, 'create']); // 型付け終了
    Route::put('/invitation/update', [InvitationController::class, 'update']); // 型付け終了
    Route::delete('/invitation/delete', [InvitationController::class, 'delete']); // 型付け終了
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'auth'], function() {
    Route::controller(\App\Http\Controllers\API\AuthController::class)->group(function() {
        Route::post('register','register');
        Route::post('login','login');
        Route::get('send-mail', 'testMail');
        Route::post('forget-password-request', 'forgetPasswordRequest');
        Route::post('forget-password', 'verifyAndChangePassword');
    });
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::controller(\App\Http\Controllers\API\AuthController::class)->group(function() {
            Route::get('logout', 'logout');
            Route::get('get-profile', 'getProfile');
            Route::post('change-password', 'changePassword');
            Route::post('update-profile', 'updateProfile');
        });
    });
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::group(['prefix' => 'user'], function(){
        Route::apiResource('posts', \App\Http\Controllers\API\PostController::class);
        Route::get('posts-public', [\App\Http\Controllers\API\PostController::class, 'publicPosts']);

        Route::controller(\App\Http\Controllers\API\LikeCommentController::class)->group(function(){
            Route::post('comments', 'PostComment');
            Route::get('like/{postId}', 'LikeUnlike');
        });
        Route::controller(\App\Http\Controllers\API\UserController::class)->group(function(){
            Route::get('notifications/{id}', 'markNotificationComplete');
            Route::get('notifications', 'markAllNotificationComplete');
        });
    });
});

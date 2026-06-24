<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('test', function(){
    return 'Valider';
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function(){

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/add', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users/update/{id}', [UserController::class, 'update']);
    Route::get('/users/supprimer/{id}', [UserController::class, 'destroy']);

    Route::get('/urls', [UrlController::class, 'index']);
    Route::post('/urls/add', [UrlController::class, 'store']);
    Route::get('/urls/{id}', [UrlController::class, 'show']);
    Route::post('/urls/update/{id}', [UrlController::class, 'update']);
    Route::get('/urls/supprimer/{id}', [UrlController::class, 'destroy']);

      Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments/add', [CommentController::class, 'store']);
    Route::get('/comments/{id}', [CommentController::class, 'show']);
    Route::post('/comments/update/{id}', [CommentController::class, 'update']);
    Route::get('/comments/supprimer/{id}', [CommentController::class, 'destroy']);

    Route::get('/likes', [LikeController::class, 'index']);
    Route::post('/likes/add', [LikeController::class, 'store']);
    Route::get('/likes/{id}', [LikeController::class, 'show']);
    Route::post('/likes/update/{id}', [LikeController::class, 'update']);
    Route::get('/likes/supprimer/{id}', [LikeController::class, 'destroy']);

});



// Route::apiResource('urls', UrlController::class);
// Route::apiResource('comments', CommentController::class);
// Route::apiResource('likes', LikeController::class);

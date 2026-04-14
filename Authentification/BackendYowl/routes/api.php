<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\signupController;
use App\Http\Controllers\signinController;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('test', function(){
    return 'Valider';
});

Route::post('signup', [signupController::class, 'store']);
Route::get('signin', [signinController::class, 'get']);

Route::get('/acceuil', [UserController::class, 'index']);
Route::get('/creer', [UserController::class, 'create']);
Route::post('/enregistrer', [UserController::class, 'store']);
Route::get('/modifier/{id}', [UserController::class, 'show']);
Route::post('/update/{id}', [UserController::class, 'update']);
Route::get('/supprimer/{id}', [UserController::class, 'destroy']);

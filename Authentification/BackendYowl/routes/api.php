<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\signupController;
use App\Http\Controllers\signinController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('test', function(){
    return 'Valider';
});

Route::post('signup', [signupController::class, 'store']);

Route::get('signin', [signinController::class, 'get']);

<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('test', function(){
    return 'Valider';
});

//au lieu de faire une route pour chaque methode on va englober tout en un vu que ici on travaile avec api
Route::apiResource('users', UserController::class);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\signupController;
use App\Http\Controllers\signinController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\commentController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
    return $request->user();
    });
    //Route pour ajouter un commentaire
    Route::post('/add-comment', [commentController::class, 'store']);
    //Route update un commentaire
    Route::post('/comment-update/{id}', [commentController::class, 'update']);
    //Route modification d'un commentaire
    Route::get('/comment-modifier/{id}', [commentController::class, 'show']);


    //CRUD PROFILE USER
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users/add', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::patch('/users/update/{id}', [UserController::class, 'update']);
    Route::delete('/users/supprimer/{id}', [UserController::class, 'destroy']);
});

//Tous les commentaires
Route::get('/comments', [commentController::class, 'index']);



// Route test pour tester l'API
Route::get('test', function(){
    return 'Valider';
});

//ROUTE SYSTEM D'AUTHENTIFICATION
Route::post('signup', [signupController::class, 'store']);
Route::post('signin', [signinController::class, 'get']);




//Tous les commentaires
//Route::get('/comments', [commentController::class, 'index']);



//Route::get('/creer', [commentController::class, 'create']);

//Route::get('/modifier/{id}', [commentController::class, 'show']);
//Route::post('/update/{id}', [commentController::class, 'update']);
//Route::get('/supprimer/{id}', [commentController::class, 'destroy']);



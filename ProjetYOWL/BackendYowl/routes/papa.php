<?php
use GuzzleHttp\Middleware;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:api')->get('/users', function(Request $request){
    return Auth::user();
});

Route::get('test', function(){
    return 'Valider';
});
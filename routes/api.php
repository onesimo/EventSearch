<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

//Public routes
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

//Private routes
Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('/event',[EventController::class,'list'])->middleware('request.logging');
    Route::post('/logout',[AuthController::class,'logout']);
});


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\EventController;

//Public routes
Route::post('/register',[AuthenticationController::class,'register']);
Route::post('/login',[AuthenticationController::class,'login']);

//Private routes
Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('/event',[EventController::class,'list'])->middleware('request.logging');
    Route::post('/logout',[AuthenticationController::class,'logout']);
});


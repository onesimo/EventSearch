<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

//Public routes
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::get('/', function () {
    return redirect('https://app.swaggerhub.com/apis-docs/onesimo/EventSearchDev/1.0.0#/');
});

//Private routes
Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('/event', [EventController::class,'list'])->middleware('request.logging');
    Route::post('/logout', [AuthController::class,'logout']);
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //return $request->user();
});*/

Route::post('/register',[AuthenticationController::class,'register']);

Route::group(['middleware'=>['auth:sanctum']], function () {
    //Route::get['']
    Route::get('/user',function(){
        return "authenticated";
    });

    Route::get('/event',[EventController::class,'list']);

});


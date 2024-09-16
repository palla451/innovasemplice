<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('oauth/v1.0')->group(function (){

        Route::post('login',[AuthController::class,'login']);
        Route::post('refresh_token',[AuthController::class,'refresh_token']);

        Route::middleware(['auth:api'])->group(function (){
            Route::get('logged', [AuthController::class,'logged']);
            Route::get('logout', [AuthController::class,'logout']);
            Route::get('users',[UserController::class, 'index']);

           Route::get('test',function (){
              return 'ok';
           });
        });
});

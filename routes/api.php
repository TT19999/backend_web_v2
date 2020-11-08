<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JwtAuthController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login',[JwtAuthController::class, 'login']);
Route::post('/register', [JwtAuthController::class, 'register']);
  
Route::group(['middleware' => 'auth.jwt'], function () {
 
    Route :: get('logout',[JwtAuthController::class, 'logout']);
    Route :: get('user-info', [JwtAuthController::class, 'getUser']);
});

Route :: get('/getUser', [JwtAuthController::class, 'getAllUser']);
Route :: get('/check', [UserController::class, 'checkView']);
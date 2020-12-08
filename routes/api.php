<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JwtAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
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

    // profile
    Route :: get('/profile/getUserInfo', [ProfileController::class,'getUserInfo']);
    Route :: put('/profile/editUserInfo',[ProfileController::class,'editUserInfo']);
    Route :: post('/profile/editAvatar',[ProfileController::class,'editAvatar']);
    Route :: post('/profile/editCover',[ProfileController::class,'editCover']);
    Route :: delete('/profile/delete',[ProfileController::class,'deleteUser']);


    //trip  
    Route :: post('/trip/create', [TripController::class,'createTrips']);
    Route :: put('/trip/edit',[TripController::class,'editTrips']);
    Route :: delete('/trip/delete', [TripController::class,'deleteTrips']);
    Route :: post('/trip/updateCover/{trip}',[TripController::class,'updateCover']);
    Route :: post('/trip/addImage/{trip}',[TripController::class,'addImage']);
    Route :: get ('/trip/user', [TripController::class, 'userTrips']);


    //user
    Route::post('/user/update', [UserController::class,'BecomeContributor']);
    Route::get('/admin/getRequestUser', [UserController::class, 'GetAllRequestContributor']);
    Route::post('/admin/updateUser',[UserController::class, 'setContributor']);

    
});

    Route::get('/order',[OrderController::class,'index']);
Route::post('/order/create',[OrderController::class,'create']);

Route :: get('/trip/byId', [TripController::class,'getTripById']);
Route :: get('/trip', [TripController::class,'getAllTrip']);
Route :: post('/trip/search',[TripController::class,'search']);
Route :: post('/trip/searchTripByLocation', [TripController::class,'searchTripByLocation']);
Route :: get('/trip/location', [TripController::class,'getAllLocation']);

    Route :: get('/check', [UserController::class, 'checkView']); //test
    Route :: post('/testimage', [UserController::class, 'testImage']);//test
    Route :: get('/indeximage', [UserController::class, 'index']) -> name('image.index');//test
Route :: get('/trip/getImage/{trip}',[TripController::class,'getImage']);
Route ::get('/trip/city', [TripController::class, 'getAllCity']);
Route::get('/trip/city/{city}',[TripController::class, 'getAllTripInCity']);




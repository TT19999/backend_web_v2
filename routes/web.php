<?php

use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use App\Mail\HelloWorldMail;
use App\Mail\Order;
use App\Models\Order as ModelsOrder;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $order= DB::table('orders')->where('date','<',now())->get();
    return response()->json($order);

});

Route::get('/sendEmail' , [MailController::class, 'order']);

Route::get('/notify',[UserController::class,'getNotification']);
<?php

use App\Http\Controllers\MailController;
use App\Mail\HelloWorldMail;
use App\Mail\Order;
use App\Models\Order as ModelsOrder;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Arr;
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
   $array = [];
   $trip=Trip::find(6);
   $array=$trip;
   $user=User::find($trip->user_id);
   $owner=User::find(3);
   $order=ModelsOrder::find(1);
   Mail::to('tunghust99@gmail.com')->send(new Order($trip,$user,$owner,$order));

    return view('emails.order',['trip'=>$trip, 'user'=>$user,'owner'=>$owner,'order'=>$order]);
});

Route::get('/sendEmail' , [MailController::class, 'order']);

Route::get('/notify',function(){
    $user=User::find(40);
    
});
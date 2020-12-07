<?php

use App\Http\Controllers\MailController;
use App\Mail\HelloWorldMail;
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
    Mail::to('tunghust99@gmail.com')->send(new HelloWorldMail());
    return view('welcome');
});

Route::get('/sendEmail' , [MailController::class, 'order']);
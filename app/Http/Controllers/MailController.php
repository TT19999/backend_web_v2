<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function order(Request $request, $orderId)
    {
        $order = Trip::findOrFail($orderId);

        Mail::to($request->user())->send($order);

        if(Mail::failures()){
            return 'false';
        }
        else return 'true';
    }
}

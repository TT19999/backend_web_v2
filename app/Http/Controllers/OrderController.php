<?php

namespace App\Http\Controllers;

use App\Mail\HelloWorldMail;
use App\Mail\Order as MailOrder;
use App\Models\Order;
use App\Models\Trip;
use App\Models\User;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;


class OrderController extends Controller
{
    public function index(){
        // $user = JWTAuth:: parseToken() ->authenticate();
        // $role = $user->getRole()->first()->name;
        // if($role=="admin"){
            $orders= Order::all();
            foreach ($orders as $order){
                $user=\App\Models\User::findorfail($order->user_id);
                $owner=\App\Models\User::findorfail($order->owner_id);
                $trip=Trip::findorfail($order->trip_id); 
                $order->user=$user->name;
                $order->owner=$owner->name;
                $order->trip=$trip->name;
            }
            return \response()->json(compact('orders'));

        // }
        // else {
        //     return response()->json([
        //         "status_code" => 400,
        //         "message" => "không thể thực hiện chức năng này",
        //     ]);
        // }
    }

    public function create(Request $request){
        $order=Order::create($request->all());
        $trip=Trip::find($request->trip_id);
        $user=User::find($request->user_id);
        $owner=User::find($request->owner_id);
        Mail::to('tunghust99@gmail.com')->send(new MailOrder($trip,$user,$owner,$order));
        // Mail::to('tuntun9xbaby@gmail.com')->send(new HelloWorldMail($trip));

        return \response()->json(compact('order'));
    }
}

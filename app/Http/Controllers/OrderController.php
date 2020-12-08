<?php

namespace App\Http\Controllers;

use App\Mail\HelloWorldMail;
use App\Mail\Order as MailOrder;
use App\Models\Order;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\InvoicePaid;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;


class OrderController extends Controller
{
    public function index(){
        $user = JWTAuth:: parseToken() ->authenticate();
        $role = $user->getRole()->first()->name;
        if($role=="admin"){
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

        }
        else {
            return response()->json([
                "status_code" => 400,
                "message" => "không thể thực hiện chức năng này",
            ]);
        }
    }

    public function create(Request $request, Trip $trip){
       
        $user = JWTAuth :: parseToken() ->authenticate();
        $owner= User::find($trip->user_id);
        $order=Order::create([
            'user_id'=>$user->id,
            'owner_id'=>$owner->id,
            'date'=>$request->date,
            'trip_id'=>$trip->id,
            'participants'=>$request->participants
        ]);
        $user->notify(new InvoicePaid($trip,$user, $owner, $order));
        $owner->notify(new InvoicePaid($trip,$user, $owner, $order));
        // Mail::to('tuntun9xbaby@gmail.com')->send(new HelloWorldMail($trip));

        return \response()->json(
            [
                'status_code'=>201,
                'order'=>$order,
            ],201
        );
    }

    public function show(){
        $user = JWTAuth :: parseToken() ->authenticate();
        $order = Order::where('user_id','=',$user->id)->get();
        return \response()->json([
            'status_code'=>200,
            'user'=>$user,
            'order'=>$order,
        ],200);
    }
}

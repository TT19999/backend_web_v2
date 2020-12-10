<?php

namespace App\Http\Controllers;

use App\Mail\HelloWorldMail;
use App\Mail\Order as MailOrder;
use App\Models\Order;
use App\Models\Trip;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Aws\CodeStarNotifications\Exception\CodeStarNotificationsException;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Swift_TransportException;
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
                "message" => "Can not do this action",
            ]);
        }
    }

    public function create(Request $request,$id){

        $validate = Validator::make($request->all() ,[
            'date' => 'required',
            'participants'=> 'required',
        ]);
        if($validate ->fails()){
            return ErrorsController::requestError('data is not enough or error');
        }
        $trip=Trip::find($id);
        if($trip==null){
            return \response()->json([
                'status_code'=> 400,
                'message'=>'khong tim thay trip',
            ],400);
        }
        $user = JWTAuth :: parseToken() ->authenticate();
        $owner= User::find($trip->user_id);
        
        
        $order=Order::create([
            'user_id'=>$user->id,
            'owner_id'=>$owner->id,
            'date'=>$request->date,
            'trip_id'=>$trip->id,
            'participants'=>$request->participants
        ]);

        
        try{

            $user->notify(new InvoicePaid($trip,$user, $owner, $order));
            $owner->notify(new InvoicePaid($trip,$user, $owner, $order));
        }catch(Swift_TransportException $a ){
            return \response()->json(
                [
                    'status_code'=>201,
                    'order'=>$order,
                    'email'=>'email se dc admin gui den sau'
                ],201
            ); 
        }
        
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

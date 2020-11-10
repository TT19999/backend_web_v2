<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Trip;
use App\Models\Image_trip;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Validator;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Hash;

class TripController extends Controller
{
    public function createTrips(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        if($user->can('create', Trip::class)){

            $trip=Trip::create([
                $request->all(),
                'user_id' => $user->id,
            ]);
            return \response()->json([
                'success' => true,
                'trips' => $trip,
            ]);
        }
        else return \response()->json([
            'success' => false,
            'message' => 'user cant create trips',
        ]);
    }

    public function editTrips(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        $trip=Trip::find($request->trip_id);
        if($trip){
            if($user->can('update', $trip )){
                $trip->update($request->all());
                return response()->json([
                    'success' => true,
                    'trip' => $trip
                ]);
            }else return response()->json([
                'success' => false,
                'message' => 'user cant edit trip'
            ]);
        }
        else return response()->json([
            'success' => false,
            'message' => 'dont have trip'
        ]); 
    }

    public function deleteTrips(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        $trip=Trip::find($request->trip_id);
        if($trip){
            if($user->can('update', $trip )){
                $trip->delete();
                return response()->json([
                    'success' => true,
                ]);
            }
            else return response()->json([
                'success' => false,
                'message' => 'user cant delete trip'
            ]);
        }
        else return response()->json([
            'success' => false,
            'message' => 'dont have trip'
        ]); 
    }
}

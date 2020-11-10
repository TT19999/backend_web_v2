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
use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect;

class TripController extends Controller
{


    public function getAllTrip(){
        return Trip::get();
    }

    public function getTripById(Request $request){
        return Trip::find($request->trip_id);
    }

    public function createTrips(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        if($user->can('create', Trip::class)){

            $trip=Trip::create([
                'user_id' => $user->id,
                'name'=>$request->name,
                'description'=>$request->description,
                'cover'=>$request->cover,
                'location'=>$request->location,
                'duration'=>$request->duration,
                'departure'=>$request->departure,
                'price'=>$request->price,
                'languages'=>$request->languages,
                'group-size'=>$request->group_size,
                'categories'=>$request->categories,
                'transportation'=>$request->transportation,
                'includes'=>$request->includes,
                'excludes'=>$request->excludes,
                
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
            if($user->can('delete', $trip )){
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


    public function searchTripByLocation(){
        Trip::select('*')->where('location');
    }

    public function search(Request $request)
    {

        $searchterm = $request->input('query');

        $searchResults = (new Search())
            ->registerModel(Trip::class, ['name', 'location'])
            ->perform($searchterm);

        return \response()->json(\compact('searchResults','searchterm'));
    }


    public function updateCover(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();

        if($request->hasFile('cover')){
            $fileName = time().'_'.$request->cover->getClientOriginalName();
            $path = Storage::putFileAs('coverTrip', $request->cover,$fileName);
            $trip = Trip::where('user_id','=',$request->trip_id)->first();
            $trip ->update([
                'cover' => $path,
            ]);
            return \response()->json([
                'success' => true,
                'path' => $path,
            ]);
        }
        else {
            return \response()->json([
                'success' => false,
                'message' => 'not found file'
            ]);
        }
    }

}


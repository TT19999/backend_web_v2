<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Trip;
use App\Models\Image_trip;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
use Hash;
use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect;
use App\Http\Controllers\ErrorsController;


class TripController extends Controller
{


    public function getAllTrip(){
        try{
            $trips = DB::table('new_trips')->join('users','new_trips.user_id','=','users.id')
                        ->join('user_info', 'user_info.user_id','=','new_trips.user_id')
                        ->select('new_trips.*', 'users.name as userName', 'user_info.avatar as userAvatar')
                        ->get();
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
        return response()->json([
            'trips' => $trips,
            'status_code' => '200',
        ],200);
    }

    public function getTripById(Request $request){
        try{
            $trip = Trip::find($request->trip_id);
            $user = DB::table('users')
                    ->join('user_info','users.id','=','user_info.user_id')
                    ->select('users.name','user_info.avatar','users.id')->first(); 
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
        return response()->json([
            'trip' => $trip,
            'user' => $user,
            'status_code' => '200',
        ],200);
    }

    public function userTrips(){
        $user = JWTAuth :: parseToken() ->authenticate();
        try{
            $trip = Trip::where('user_id', '=', $user->id)->get();
            return response()->json([
                'status_code'=> '200',
                'trips' => $trip
            ]);
            
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
    }

    public function createTrips(Request $request){
        $validate = Validator::make($request->all() ,[
            'name' => 'required|max:255',
            'description'=> 'required',
            'location' => 'required',
            'duration' => 'required',
            'departure' => 'required',
            'price' => 'required',
            'group_size' => 'required',
            'city' =>'required',
        ]);
        if($validate ->fails()){
            return ErrorsController::requestError('data is not enough or error');
        }
        $user = JWTAuth :: parseToken() ->authenticate();
        if($user->can('create', Trip::class)){
            try{
                $trip=Trip::create([
                    'user_id' => $user->id,
                    'name'=>$request->name,
                    'description'=>$request->description,
                    'cover'=>$request->cover ?$request->cover : 'cover/default.png',
                    'location'=>$request->location,
                    'duration'=>$request->duration,
                    'departure'=>$request->departure,
                    'price'=>$request->price,
                    'languages'=>$request->languages,
                    'group_size'=>$request->group_size,
                    'categories'=>$request->categories,
                    'transportation'=>$request->transportation,
                    'includes'=>$request->includes,
                    'excludes'=>$request->excludes,
                    'city' => $request->city,
                ]);
                return response()->json([
                    'trip' => $trip,
                    'status_code' => '201',
                ]);
            }catch(\Illuminate\Database\QueryException $ex){
                return ErrorsController::internalServeError('');
            }

        }else {
            return ErrorsController::forbiddenError();
        }
        
        
    }

    public function editTrips(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        try{
            $trip=Trip::find($request->trip_id);
            if($trip){
                if($user->can('update', $trip )){
                    $trip->update($request->all());
                }else return ErrorsController::forbiddenError();
            }
            else return ErrorsController::requestError('not has trip');
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
        return response()->json([
            'status_code' => '200',
            'success' => true,
            'trip' => $trip
        ],200);
    }

    public function deleteTrips(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        try{
            $trip=Trip::find($request->trip_id);
            if($trip){
                if($user->can('delete', $trip )){
                    $trip->delete();
                }
                else return ErrorsController::forbiddenError();
            }
            else return ErrorsController::requestError('not has trip');
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
        return \response()->json([
            'status_code' => '200',
            'message' => 'success',
        ],200);
    }

    public function getAllLocation(){
        try{
            $location = DB::table('new_trips')->select('location')->distinct()->get();
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
        return response()->json([
            'location'=> $location,
            'status_code' => '200',
        ],200);
    }

    public function searchTripByLocation(Request $request){
        try{
            $searchterm = $request->input('location');
            $searchResults = (new Search())
            ->registerModel(Trip::class, function (ModelSearchAspect $modelSearchAspect) {
                $modelSearchAspect
                    ->addExactSearchableAttribute('location');// only return results that exactly match
            })
            ->perform($searchterm);
 
            return \response()->json([
                'searchResults' => $searchResults,
                'searchterm' => $searchterm,
                'status_code' => '200',
            ],200);
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
    }

    public function search(Request $request)
    {
        try{
            $searchterm = $request->input('query');

            $trips = DB::table('new_trips')->join('users','new_trips.user_id','=','users.id')
                        ->join('user_info', 'user_info.user_id','=','new_trips.user_id')
                        ->select('new_trips.*', 'users.name as userName', 'user_info.avatar as userAvatar')
                        ->where('new_trips.name','like','%'.$searchterm.'%')
                        ->orWhere('new_trips.location','like','%'.$searchterm.'%')
                        ->get();

            return \response()->json([
                'trips' => $trips,
                'searchterm' => $searchterm,
                'status_code' => '200',
            ],200);
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
    }


    public function updateCover(Request $request, Trip $trip){
        $user = JWTAuth :: parseToken() ->authenticate();
        if($request->hasFile('cover')){
            try{
                $fileName = time().'_'.$request->cover->getClientOriginalName();
                $path = Storage::putFileAs('coverTrip', $request->cover,$fileName);
                $trip ->update([
                    'cover' => $path,
                ]);
                return \response()->json([
                    'status_code' => '201',
                'success' => true,
                'path' => $path,
                ],201);
            }catch(\Illuminate\Database\QueryException $ex){
                return ErrorsController::internalServeError('');
            }
        }
        else {
            return ErrorsController::requestError('not has cover');
        }
    }

    public function addImage(Request $request,Trip $trip){
        $user = JWTAuth :: parseToken() ->authenticate();
        if($user->can('update', $trip)){
            foreach ($request->all() as $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $path = Storage::putFileAs('files', $file,$fileName);
                try{
                    Image_trip::insert([
                            'trip_id' => $trip->id,
                            'path' => $path
                        ]);
                }catch(\Illuminate\Database\QueryException $ex){
                    return ErrorsController::internalServeError('');
                }
            }
            return response()->json([
                'status_code' => '201',
                'success' => true,
            ],201);
        }
        else return ErrorsController::forbiddenError();
    }

    public function getImage(Trip $trip){
        try{
            $image_trip = Image_trip :: where('trip_id','=',$trip->id)->get();
            return response()->json([
            'status_code' => '200',
            'image_trip'=> $image_trip,
        ],200);
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
        
    }

    public function getAllCity(){
        try{
            $city = Trip::select('city')->distinct()->get();

        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
        return response()->json([
            'status_code' => '200',
            'city'=>  $city,
        ],200);
    }

    public function getAllTripInCity(Request $request){
        try{
            $trips = Trip::where('city','=',$request->city)->get();
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('');
        }
        return response()->json([
            'status_code' => '200',
            'trips'=>  $trips,
        ],200);
    }
}


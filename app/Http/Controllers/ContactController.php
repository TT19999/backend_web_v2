<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ContactController extends Controller
{
    public function create(Request $request){
        $validate = Validator::make($request ->json()->all() ,[
            'description' => 'required',
            'email'=>'required|email',
            'name'=>'required',
        ]);
        if($validate ->fails()){
            return ErrorsController::requestError('data is wrong');
           
        }
        $contact=DB::table('contact')->insert([
            'email'=>$request->email,
            'name'=>$request->name,
            'description'=>$request->description
        ]);
        return \response()->json([
            'status_code'=>201,
        ],201);
    }

    public function index(){
        $user = JWTAuth :: parseToken() ->authenticate();
        $role = $user->getRole()->first()->name;
        if($role=="admin"){
            $contacts=DB::table('contact')->get();
            return response([
                'contacts'=>$contacts,
            ],200);
        }else {
            return response()->json([
                "status_code" => 400,
                "message" => "Can not do this action",
            ]);
        }
    }

    public function delete($id){
        $user = JWTAuth :: parseToken() ->authenticate();
        $role = $user->getRole()->first()->name;
        if($role=="admin"){
            DB::table('contact')->where('id','=',$id)->delete();
            return response([
                'status_code'=>true,
            ],200);
        }else {
            return response()->json([
                "status_code" => 400,
                "message" => "Can not do this action",
            ]);
        }
    }
}

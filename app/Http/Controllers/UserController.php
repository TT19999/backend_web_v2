<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_info;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;

use Validator;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\Http\Foundation\Response;
use \Illuminate\Support\Carbon;

use Hash;
use Illuminate\Support\Facades\DB ;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Tymon\JWTAuth\Facades\JWTAuth ;

class UserController extends Controller
{
    public function checkView(){
        return(JWTAuth::user());
    }

    public function index(){
        $allFile = Storage::allFiles('files');
        $files =[];
        foreach ($allFile as $file) {
            \array_push($files, Storage::url($file));
            # code...
        }
        return response() -> json(\compact('files'));
    }

    public function testImage(Request $request){
        return ($request->files->count());
        foreach ($request->files as $file) {
            $fileName = time().'_'.$file->getClientOriginalName();
            $path = Storage::putFileAs('files', $file,$fileName,'avatar');
        }
        
        return redirect()->route('image.index');
    }

    public function BecomeContributor(Request $request){
        $validate = FacadesValidator::make($request->all() ,[
            'address'=> 'required',
            'intro'=>'required',
            'languages' => 'required',
            'experiences' => 'required',
        ]);
        if($validate ->fails()){
            return ErrorsController::requestError('data is not enough or error');
        }
        $user = JWTAuth :: parseToken() ->authenticate();
        $role = $user->getRole()->first()->name;
        if($role == "user"){
            try{
                if(DB::table("update_contributor")->where("user_id","=",$user->id)->exists()){
                    return \response()->json([
                        'status_code' => 400,
                        'message' => "you are sign up",
                    ]);
                }
                else {
                    DB::table("update_contributor")->insert([
                        "user_id"=>$user->id,
                        "intro"=>$request->intro,
                        "languages"=>$request->languages,
                        "address"=>$request->address,
                        "experiences"=>$request->experiences,
                        "created_at" => Carbon::now(),
                    ]);
                    return \response()->json([
                        "status_code" => 201,
                        "message" => "your data is send, wait for admin",
                    ]);
                }
            }catch(\Illuminate\Database\QueryException $ex){
                return ErrorsController::internalServeError('Internal Serve Error');
            }
        }
        else {
            return \response()->json([
                "status_code"=> 400,
                "message"=>"nguoi dung khong the dang ki",
            ]);
        }
    }

    public function GetAllRequestContributor(){
        $user = JWTAuth :: parseToken() ->authenticate();
        $role = $user->getRole()->first()->name;
        if($role=="admin"){
            try{
                $request=DB::table('update_contributor')->get();
                return \response()->json([
                    "status_code"=>200,
                    "request" => $request,
                ]);;
            }catch(\Illuminate\Database\QueryException $ex){
                return ErrorsController::internalServeError('Internal Serve Error');
            }
        }
        else{
            return \response()->json([
                "status_code"=>400,
                "message" => "khong the xem thong tin nay",
            ]);
        }
    }

    public function setContributor(Request $request){
        
        $user = JWTAuth :: parseToken() ->authenticate();
        $role = $user->getRole()->first()->name;
        if($role=="admin"){
            if($request->action == "delete" ) {
                
                DB::table("update_contributor")->where("user_id","=",$request->user_id)->delete();
                return \response()->json([
                    "status_code" => 200,
                    "message" => "da xoa",
                ]);
            }
            if($request->action == "update"){
                DB::table("role_user")->where("user_id","=",$request->user_id)->update([
                    "role_id" => "3"
                ]);
                DB::table("update_contributor")->where("user_id","=",$request->user_id)->delete();
                return \response()->json([
                    "status_code" => 201,
                    "message" => "update done",
                ]);
            }
        }
        else {
            return response()->json([
                "status_code" => 400,
                "message" => "you can not do this action",
            ]);
        }
    }

    public function deleteUser(User $user){
        $user->delete();
        return response()->json([
            'succes'=>'true',
        ]);
    }

    public function getNotification(){
        $user = JWTAuth :: parseToken() ->authenticate();
        return \response()->json([
            'notifications'=> $user->notifications,
        ],200);
    }
}

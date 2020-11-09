<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_info;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Validator;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Hash;

class ProfileController extends Controller
{
    public function getUserInfo(){
        try{
            if(! $user = JWTAuth :: parseToken() ->authenticate()){
                return response() -> json(['user_not_found'],404);
            }
        }catch(Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json(['token_invalid'],$e->getStatusCode());
        }catch(Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json(['token_invalid'],$e->getStatusCode());
        }catch(Tymon\JWTAuth\Exceptions\JWTException $e){
            return response()->json(['token_invalid'],$e->getStatusCode());
        }

        $data=DB::table('users')->join('user_info', 'users.id', '=','user_info.user_id')
                            ->select('users.name','users.email','user_info.*')
                            ->where('users.id',$user->id)->get();
        $role = $user->getRole()->first()->name;

        return response()->json([
            'data' => $data,
            'role' => $role,
        ]);
        
    }
}

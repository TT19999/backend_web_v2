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

    public function editUserInfo(Request $request) {
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
        $user_id= $request->input('user_id') ? $request->input('user_id') : $user->id;
        $user_info = User_info::where('user_id','=',$user_id)->first();
        if($user->can('restore', $user_info)){
            $user_info->update($request->all());
            return response() ->json([
                'success' => true,
                'user_info' => $user_info,
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'error' => 'u cant edit this profile',
            ]);
        }
    }

    public function editAvatar(Request $request){
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

        if($request->hasFile('avatar')){
            $fileName = time().'_'.$request->avatar->getClientOriginalName();
            $path = Storage::putFileAs('avatar', $request->avatar,$fileName);
            $user_info = User_info::where('user_id','=',$user->id)->first();
            $user_info->update([
                'avatar' => $path,
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

    public function editCover(Request $request){
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

        if($request->hasFile('cover')){
            $fileName = time().'_'.$request->cover->getClientOriginalName();
            $path = Storage::putFileAs('cover', $request->cover,$fileName);
            $user_info = User_info::where('user_id','=',$user->id)->first();
            $user_info ->update([
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

    public function deleteUser(Request $request){
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
        $userDelete = User::find($request->user_id);
        if($userDelete){
            if($user->can('delete', $userDelete)){
                $user_info= User_info::where('user_id','=', $userDelete->id)->delete();
                $userDelete-> delete();
                return response()->json([
                    'success' =>true,
                ]);
            }
            else{
                return \response()->json([
                    'success'=>false,
                ]);
            }
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'dont have user'
            ]);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_info;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ErrorsController;

class ProfileController extends Controller
{
    public function getUserInfo(){
        try {
            $user = JWTAuth :: parseToken() ->authenticate();
            $data=DB::table('users')->join('user_info', 'users.id', '=','user_info.user_id')
                                ->select('users.name','users.email','user_info.*')
                                ->where('users.id',$user->id)->first();
            $role = $user->getRole()->first()->name;

        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('Internal Serve Error',500);
        }

        return response()->json([
            'status_code' => '200',
            'data' => $data,
            'role' => $role,
        ]);
    }

    public function editUserInfo(Request $request) {
        try{
            $user = JWTAuth :: parseToken() ->authenticate();
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
                return ErrorsController::forbiddenError();
            }
        }catch(\Illuminate\Database\QueryException $ex){
                return ErrorsController::internalServeError('Internal Serve Errors');
        }

    }

    public function editAvatar(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        try{
            if($request->hasFile('avatar')){
                $fileName = time().'_'.$request->avatar->getClientOriginalName();
                $path = Storage::putFileAs('avatar', $request->avatar,$fileName);
                $user_info = User_info::where('user_id','=',$user->id)->first();
                $user_info->update([
                    'avatar' => $path,
                ]);
                return \response()->json([
                    'status_code' => '201',
                    'success' => true,
                    'path' => $path,
                ],201);
            }else {
                return ErrorsController::requestError('Không có dữ liệu avatar');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('Internal Serve Error');
        }


    }

    public function editCover(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        try {
            if($request->hasFile('cover')){
                $fileName = time().'_'.$request->cover->getClientOriginalName();
                $path = Storage::putFileAs('cover', $request->cover,$fileName);
                $user_info = User_info::where('user_id','=',$user->id)->first();
                $user_info ->update([
                    'cover' => $path,
                ]);
                return \response()->json([
                    'status_code' => '201',
                    'success' => true,
                    'path' => $path,
                ],201);
            }
            else {
                return ErrorsController::requestError('Không có dữ liệu ảnh bìa');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('Internal Serve Error');
        }

    }

    public function deleteUser(Request $request){
        $user = JWTAuth :: parseToken() ->authenticate();
        try{
            $userDelete = User::find($request->user_id);
            if($userDelete){
                if($user->can('delete', $userDelete)){
                    $user_info= User_info::where('user_id','=', $userDelete->id)->delete();
                    $userDelete-> delete();
                    return response()->json([
                        'status_code' => '200',
                        'success' =>true,
                    ],200);
                }
                else{
                    return ErrorsController::forbiddenError();
                }
            }
            else {
                return ErrorsController::requestError('Không tìm thấy User');
            }
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('InternalServeError');
        }

    }


}

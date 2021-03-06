<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


use App\Models\User;
use App\Models\User_info;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\Http\Foundation\Response;
use App\Models\Role;


use App\Http\Controllers\ErrorsController;

class JwtAuthController extends Controller
{
    public $token = true;

    public function register(Request $request)
    {

        $validate = Validator::make($request ->json()->all() ,[
            'name' => 'min:1',
            'email'=> 'string|email',
            'password' => 'min:6'
        ]);
        if($validate ->fails()){
            return ErrorsController::requestError('data is wrong');
            return \response() -> json($validate->errors()->toJson(),400);
        }
        DB::beginTransaction();
        try{
             $user =User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password' => Hash::make($request->json()->get('password')),
            ]);

            $role_user =DB::table('role_user')->insert([
                'role_id' => 2,
                'user_id' => $user->id,
            ]);
        $user_info=User_info::create([
            'user_id' => $user->id,
            'avatar' => 'avatar/avatar.jpg',
            'cover' => 'cover/default.png'
        ]);
        DB::commit();
        } catch (\Illuminate\Database\QueryException $ex) {
            // dd($ex->getMessage());
            return ErrorsController::requestError('email da duoc dang ky');

        }

        $resUser= $user;
        $token = JWTAuth::fromUser($user);
        // return $role;
        return \response()->json([
            'status_code'=>'201',
            'user' => $resUser,
            'token' => $token,
            'role' => 'user',
            'user_info'=>$user_info
        ],201);
    }

    public function login(Request $request)
    {
        $creadentials = $request ->json()->all();

        // return \response()->json($creadentials);
        try{
            if(! $token = JWTAuth::attempt($creadentials)){
                return ErrorsController::requestError('Auth Fail!!');
            }
        }catch(JWTException $e){
            return ErrorsController::internalServeError('Internal Serve Error');
        }
        $user = JWTAuth::user();
        $user_info=DB::table('user_info')->where('user_id','=',$user->id)->get();
        return \response()->json([
            'status_code'=>'200',
            'token' => $token,
            'role' => $user->getRole()->first()->name,
            'user_info'=>$user_info
        ],200);

        // $input = $request->only('email', 'password');
        // $jwt_token = null;

        // if (!$jwt_token = JWTAuth::attempt($input)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Invalid Email or Password',
        //     ], Response::HTTP_UNAUTHORIZED);
        // }

        // $user = JWTAuth::authenticate($jwt_token);
        // $role = $user->getRole()->first()->name;
        // return response()->json([
        //     'success' => true,
        //     'token' => $jwt_token,
        //     'role' => $role,
        // ]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth :: parseToken()->invalidate();

            return response()->json([
                'status_code' => '200',
                'success' => true,
                'message' => 'User logged out successfully'
            ],200);
        } catch (JWTException $exception) {
            return ErrorsController::internalServeError('not auth');
        }
    }

    public function getUser(Request $request)
    {
        $user = JWTAuth :: parseToken() ->authenticate();
        try{
            $role = $user->getRole()->first()->name;
            $resuser = JWTAuth::user();
        }catch(\Illuminate\Database\QueryException $ex){
            return ErrorsController::internalServeError('Internal Serve Error');
        }
        return response()->json([
            'status_code' => '200',
            'success' => true,
            'user' => $resuser,
            'role' => $role,
        ],200);
    }

    public function getAllUser(){
        return User::get();
    }

    public function resetPassword(Request $request){
        $validate = Validator::make($request ->json()->all() ,[
            'new_password' => 'required|min:6',
            'old_password' => 'required'
        ]);
        if($validate ->fails()){
            return ErrorsController::requestError('data is wrong');
            return \response() -> json($validate->errors()->toJson(),400);
        }

        $user = JWTAuth :: parseToken() ->authenticate();
        if(!(Hash::check($request->get('old_password'), $user->password))){
            return \response()->json([
                'status_code' => 400,
                'message' => 'mat khau cu khong chinh xacs'
            ],400);
        }

        if(strcmp($request->get('old_password'), $request->get('new_password')) == 0){
            return response()->json([
                'status_code' => 400,
                'message' => 'mat khau moi khong duoc trung voi mat khau cu'
            ],400);
        }

        $user->password = Hash::make($request->json()->get('new_password'));
        $user->save();

        return response()->json([
            'status_code'=>201,
            'message' => 'mat khau thay doi thanh cong'
        ],201);

    }
}

<?php
 
namespace App\Http\Controllers;
 
use JWTAuth;
use Validator;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\Http\Foundation\Response;
use App\Models\Role;
use DB;
use Hash;
 
class JwtAuthController extends Controller
{
    public $token = true;
  
    public function register(Request $request)
    {
 
        $validate = Validator::make($request ->json()->all() ,[
            'name' => 'min:2',
            'email'=> 'string',
            'password' => 'min:6'
        ]);
        if($validate ->fails()){
            return \response() -> json($validate->errors()->toJson(),400);
        }

        $user =User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password' => Hash::make($request->json()->get('password')),
        ]);

        $role_user =DB::table('role_user')->insert([
            'role_id' => 3,
            'user_id' => $user->id,
        ]);
        DB::table('user_info') ->insert([
            'user_id' => $user->id,
        ]);
        $resUser= $user;
        $token = JWTAuth::fromUser($user);
        // return $role;
        return \response()->json([
            'user' => $resUser,    
            'token' => $token,
            'role' => 'user',
        ],201);
    }
  
    public function login(Request $request)
    {
        $creadentials = $request ->json()->all();
        // return \response()->json($creadentials);
        try{
            if(! $token = JWTAuth::attempt($creadentials)){
                return response() -> json(['error' => 'invalid_vreadentials'],400);
            }
        }catch(JWTException $e){
            return \response()->json(['error'=>'could_not create token'],500);
        }
        $user = JWTAuth::user();
        return \response()->json([
            'token' => $token,
            'role' => $user->getRole()->first()->name,
        ]);

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
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
  
    public function getUser(Request $request)
    {
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
        $resuser = JWTAuth::user();
        return response()->json([
            'success' => true,
            'user' => $resuser,
            'role' => $user->getRole()->first()->name,
        ]);
    }

    public function getAllUser(){
        return User::get();
    }
}
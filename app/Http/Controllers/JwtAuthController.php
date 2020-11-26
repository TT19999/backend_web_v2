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
use App\Http\Controllers\ErrorsController;
 
class JwtAuthController extends Controller
{
    public $token = true;
  
    public function register(Request $request)
    {
 
        $validate = Validator::make($request ->json()->all() ,[
            'name' => 'min:6',
            'email'=> 'string|email',
            'password' => 'min:6'
        ]);
        if($validate ->fails()){
            return ErrorsController::requestError('Thông tin đăng ký không đúng');
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
                'role_id' => 1,
                'user_id' => $user->id,
            ]);
        $user_info=DB::table('user_info')->insert([
            'user_id' => $user->id,
            'avatar' => 'avatar/avatar.jpg',
            'cover' => 'cover/default.png'
        ])->get();
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
                return ErrorsController::requestError('Thông tin đăng nhập sai');
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
            return ErrorsController::internalServeError('Bạn chưa đăng nhập');
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
}
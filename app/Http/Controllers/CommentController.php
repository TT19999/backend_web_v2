<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function create(Request $request,Trip $trip){
        $validate = Validator::make($request ->json()->all() ,[
            'comment' => 'min:1',
        ]);
        if($validate ->fails()){
            return ErrorsController::requestError('data is wrong');
           
        }
        $user = JWTAuth :: parseToken() ->authenticate();
        $comment=Comment::create([
            'user_id'=>$user->id,
            'trip_id'=>$trip->id,
            'comment'=>$request->comment,
        ]);

        return \response()->json([
            'status_code'=>201,
            'comment'=>$comment,
            'user'=>$user
        ],201);
    }

    public function delete(Comment $comment){
        $user = JWTAuth :: parseToken() ->authenticate();
        $role = $user->getRole()->first()->name;
        if($role=="admin" || $user->id==$comment->user_id){
            $comment->delete();
        }else {
            return response()->json([
                "status_code" => 400,
                "message" => "Can not do this action",
            ]);
        }
        return \response()->json([
            'status_code'=>200,
        ],200);
    }

    public function index(Trip $trip){
        $comments=Comment::where('trip_id','=',$trip->id)->get();
        return \response()->json([
            'comments'=>$comments,
            'status_code'=>200,
        ],200);
    }

    public function userIndex(Request $request){
        $user = JWTAuth:: parseToken() ->authenticate();
        $userIndex=User::find($request->user_id);
        // return response()->json($userIndex);
        $role = $user->getRole()->first()->name;
        if($role=="admin"|| $user->id == $userIndex->id){
            $comments= Comment::where('user_id','=',$userIndex->id)->get();
            return \response()->json(compact('comments'));
        }
        else {
            return response()->json([
                "status_code" => 400,
                "message" => "Can not do this action",
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    static public function  requestError(String $errors){
        return \response()->json([
            'status_code' => '400',
            'message' => $errors,
        ],400);
    }
    static public function internalServeError(String $string){
        return \response()->json([
            'status_code' => '500',
            'message' => $string == '' ? 'Internal Serve Error' : $string ,
        ],500);
    }

    static public function forbiddenError(){
        return response() -> json(
            [
                'status_code' => '403',
                'message' => 'user can not do this action '
            ],403
        );
    }
}

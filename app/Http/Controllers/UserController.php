<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_info;
use App\Models\User;
use App\Models\Role;

use JWTAuth;
use Validator;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\Http\Foundation\Response;
use DB;
use Hash;

class UserController extends Controller
{
    public function checkView(){
        return(JWTAuth::user());
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_info;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    public function checkView(){
        $user = User::find(5);
        // return $user;
        $roles =Role :: find('1');
        // return $roles->permissions;
        if( $user && $user->can('update', User_info::class)){
            return response()->json('true');
        }
        return response()->json('false');
    }
}

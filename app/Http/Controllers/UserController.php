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
use Symfony\Component\Http\Foundation\Response;
use DB;
use Hash;

class UserController extends Controller
{
    public function checkView(){
        return(JWTAuth::user());
    }

    public function index(){
        $allFile = Storage::allFiles('files');
        $files =[];
        foreach ($allFile as $file) {
            \array_push($files, Storage::url($file));
            # code...
        }
        return response() -> json(\compact('files'));
    }

    public function testImage(Request $request){
        foreach ($request->files as $file) {
            $fileName = time().'_'.$file->getClientOriginalName();
            $path = Storage::putFileAs('files', $file,$fileName,'avatar');
            return $path;
        }
        
        return redirect()->route('image.index');
    }
}

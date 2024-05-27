<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    //
    public $lastModifiedC = ""; public $lastModifiedV = "";
    public function __construct()
    {
        $pathC = app_path("Http/Controllers/RedisController.php");
        $pathV = resource_path("views/welcome.blade.php");
        //return File::exists($pathC);
        $this->lastModifiedC = File::lastModified($pathC);
        $this->lastModifiedV = File::lastModified($pathV);
    }

    public function publish(){
        
        //return "C: ".date("Y-m-d H:i:s", $this->lastModifiedC)." V: ".date("Y-m-d H:i:s", $this->lastModifiedV);


        $data = ['message' => 'Hello, Farhan!'];
        
        Redis::publish('ok',json_encode($data));
        return response()->json(['status' => 'Message Published']);
    }

    public function testlain(){
        return "C: ".date("Y-m-d H:i:s", $this->lastModifiedC)." V: ".date("Y-m-d H:i:s", $this->lastModifiedV);
    }
}

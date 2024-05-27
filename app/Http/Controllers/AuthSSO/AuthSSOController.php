<?php

namespace App\Http\Controllers\AuthSSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthSSOController extends Controller
{
    //
    private $host_api = "http://192.168.2.21:6001/";

    public function authLogin($nip,$password){
        $data = [
            'appid' => 'REIM',
            'userid' => $nip,
            'password' => $password
        ];
        $http = Http::withOptions([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Connection' => 30,
            'verify' => false
        ])->post($this->host_api.'login',$data);

        $json = json_decode($http->body());

        return $json;
    }

    public function pingApiSSO(){
        $http = Http::withOptions([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Connection' => 30,
            'verify' => false
        ])->get($this->host_api.'ping');

        $json = json_decode($http->body());

        return $json;
    }
}

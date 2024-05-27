<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {

        $from = Carbon::now()->subDays(7)->format('Y-m-d');
        $to = Carbon::now()->format('Y-m-d');

        $data = DB::table('t_request')
                ->where('t_request.user_by', '=', usersCustom()->nip)
                ->whereBetween(DB::raw("DATE_FORMAT(date_req,'%Y-%m-%d')"),[$from,$to])
                ->orderBy('t_request.date_req', 'DESC')
                ->select([
                    DB::raw('sum(if(kode_result = "PENDING",1,0)) as pending, sum(if(kode_result = "PROGRESS" or kode_result = "PROGRESS_KASBON",1,0)) as progress, sum(if(kode_result = "REJECT",1,0)) as reject,sum(if(kode_result = "APPROVE",1,0)) as approve')
                ])
                ->first();

        //$data->appends($request->query());
        return view('home.index',compact('data'));
    }
}

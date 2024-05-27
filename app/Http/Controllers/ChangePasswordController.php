<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    //
    public function index(Request $request){
        return view('formchangepassword');
    }

    public function changepassword(Request $request){
        $validate = Validator::make($request->all(),[
            'oldpassword' => 'required',
            'newpassword' => 'required|min:8|confirmed'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->all()
            ]);
        }

        $nip = usersCustom()->nip;
        if(!Hash::check($request->oldpassword,usersCustom()->password)){
            return response()->json([
                'status' => 'error',
                'message' => 'Old password is incorrect.'
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('m_karyawan')->where('nip','=',$nip)->update([
                'password' => Hash::make($request->newpassword)
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['save']['success'],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['save']['error']." data : ".$th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }

    public function sendMessage(Request $request){
        $message = $request->message;

        SendMessageEvent::dispatch($message);

        return response()->json([
            'message' => 'Message has been send'
        ],200);
    }
}

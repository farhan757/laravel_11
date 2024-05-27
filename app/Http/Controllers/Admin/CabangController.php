<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CabangController extends Controller
{
    //
    public function index(Request $request){
        $data = DB::table('sys_sptcom')->where('fieldnm','=','BRANCH');
        $cari = $request->cari;

        if($cari != ''){
            $data = $data->where(function($data) use($cari){
                $data->where('description','like',"%$cari%")
                        ->orWhere('fieldcd','like',"%$cari%");
            });
        }
        $data = $data->orderBy('id','desc')->paginate(10);
        $data->appends($request->query());
        return view('admin.master.cabang.index',compact('data','cari'));
    }

    public function showformcabang()
    {
        return view('admin.master.cabang.formcabang');
    }

    public function storeCabang(Request $request){
        $validate = Validator::make($request->all(),[
            'fieldcd' => 'required',
            'description' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->all()
            ]);
        }
        
        DB::beginTransaction();
        try {
            DB::table('sys_sptcom')->insert([
                'fieldnm' => 'BRANCH',
                'fieldcd' => $request->fieldcd,
                'description' => $request->description,
                'user_by' => $this->GetUser()->nip,
                'created_at' => Carbon::now()
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['add']['success'],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['add']['error']." data : ".$th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }

    public function showEditCab(Request $request){
        $data = DB::table('sys_sptcom')->where('id','=',$request->id)->first();

        $id = $request->id;
        $fieldcd = $data->fieldcd;
        $description = $data->description;

        return view('admin.master.cabang.formcabang',compact(
            'description','fieldcd','id'
            ));
    }

    public function saveEditCab(Request $request){
        $validate = Validator::make($request->all(),[
            'fieldcd' => 'required',
            'description' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->all()
            ]);
        }

        DB::beginTransaction();
        try {
            DB::table('sys_sptcom')->where('id','=',$request->id)->update([
                'fieldnm' => 'BRANCH',
                'fieldcd' => $request->fieldcd,
                'description' => $request->description,
                'user_by' => $this->GetUser()->nip,
                'created_at' => Carbon::now()
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

    public function deleteCab(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_sptcom')->where('id','=',$request->id)->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['delete']['success']
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['delete']['error']." data : ".$th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }
}

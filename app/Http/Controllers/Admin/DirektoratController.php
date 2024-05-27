<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DirektoratController extends Controller
{
    //
    public function index(Request $request){
        $data = DB::table('sys_sptcom')
                ->select(
                    'sys_sptcom.id',
                    'sys_sptcom.fieldnm',
                    'sys_sptcom.fieldcd',
                    'sys_sptcom.description',
                    'sys_sptcom.created_at'
                )
                ->where('sys_sptcom.fieldnm', '=', 'DIREKTORAT')->where('sys_sptcom.fieldcd','!=','99');
        $cari = $request->cari;
        if($cari != ""){
            $data = $data->where(function($data) use ($cari){
                            $data->where('sys_sptcom.fieldcd','like',"%$cari%")
                            ->orWhere('sys_sptcom.description','like',"%$cari%");
            });
        }
        $data = $data->orderBy('sys_sptcom.id','desc')->paginate(10);

        $data->appends($request->query());

        return view('admin.master.direktorat.index',compact('data','cari'));
    }

    public function showformdirek()
    {
        $direk = $this->direktorat();
        return view('admin.master.direktorat.formdirektorat',compact('direk'));
    }

    public function storeDirek(Request $request){
        $validate = Validator::make($request->all(),[
            'description' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->all()
            ]);
        }
        //return $this->getNoSptCom('DEPARTMENT',$request->kode_div);
        DB::beginTransaction();
        try {
            DB::table('sys_sptcom')->insert([
                'fieldnm' => 'DIREKTORAT',
                'fieldcd' => $this->getNoSptCom('DIREKTORAT'),
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

    public function showEditDirek(Request $request){
        $data = DB::table('sys_sptcom')->where('id','=',$request->id)->first();

        $id = $request->id;
        $description = $data->description;

        return view('admin.master.direktorat.formdirektorat',compact('data',
            'description','id'
            ));
    }

    public function saveEditDirek(Request $request){
        $tmp = DB::table('sys_sptcom')->where('id','=',$request->id)->first();
        $validate = Validator::make($request->all(),[
            'description' => 'required',
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
                'fieldnm' => 'DIREKTORAT',
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

    public function deleteDirek(Request $request){
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

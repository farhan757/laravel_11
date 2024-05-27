<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Divisi2Controller extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('sys_divisi')
        ->select(
            'sys_divisi.id',
            'sys_divisi.fieldnm',
            'sys_divisi.fieldcd',
            'sys_divisi.description',
            'sys_divisi.created_at',
            'sys_direktorat.description as desc_direk'
        )
        ->leftjoin('sys_direktorat','sys_divisi.fieldcd_direk','=','sys_direktorat.fieldcd');

        $cari = $request->cari;
        if($cari !=''){
            $data = $data->where(function($data) use($cari){
                $data->where('sys_divisi.fieldnm','like',"%$cari%")
                ->orWhere('sys_divisi.fieldcd','like',"%$cari%")
                ->orWhere('sys_divisi.description','like',"%$cari%")
                ->orWhere('sys_direktorat.description','like',"%$cari%");
            });
        }
        $data = $data->orderBy('sys_divisi.id','desc')->paginate(10);
        $data->appends($request->query());
        return view('admin.master.divisi.index',compact('data','cari'));
    }

    public function showformdivisi()
    {
        // $direk = $this->direktorat();
        $direk = DB::table('sys_direktorat')
                ->select(
                'fieldcd as kode_dir',
                'description as nama_dir')
                ->get();
        return view('admin.master.divisi.formdivisi',compact('direk'));
    }

    public function storeDivisi(Request $request){
        $getNoDiv = DB::table('sys_divisi')
                    ->select('fieldcd')
                    ->orderBy('fieldcd','desc')
                    ->first();
        $NoDiv = $getNoDiv->fieldcd+1;

        $validate = Validator::make($request->all(),[
            'description' => 'required',
            'kode_dir' => 'required'
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
            DB::table('sys_divisi')->insert([
                'fieldnm' => 'DIVISI',
                'fieldcd_direk' => $request->kode_dir,
                // 'fieldcd' => $this->getNoSptCom('DIVISI',$request->kode_dir),
                'fieldcd' => $NoDiv,
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

    public function showEditDivisi(Request $request){
        $data = DB::table('sys_divisi')->where('id','=',$request->id)->first();
        $direk = DB::table('sys_direktorat')
        ->select(
            'fieldcd as kode_dir',
            'description as nama_dir')->get();

        $id = $request->id;
        $description = $data->description;
        $kode_dir = $data->fieldcd_direk;

        return view('admin.master.divisi.formdivisi',compact('data','direk',
            'description','kode_dir','id'
            ));
    }

    public function saveEditDivisi(Request $request){
        $tmp = DB::table('sys_divisi')->where('id','=',$request->id)->first();
        $validate = Validator::make($request->all(),[
            'description' => 'required',
            'kode_dir' => 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->all()
            ]);
        }

        $kode_div = $request->description;
        $tmp_desc = $tmp->description;
        $fieldcd = $tmp->fieldcd;
        if($kode_div != $tmp_desc){
            $getNoDiv = DB::table('sys_divisi')
                        ->select('fieldcd')
                        ->orderBy('fieldcd','desc')
                        ->first();
            $fieldcd = $getNoDiv->fieldcd+1;
        }

        DB::beginTransaction();
        try {
            DB::table('sys_divisi')->where('id','=',$request->id)->update([
                'fieldnm' => 'DIVISI',
                'fieldcd_direk' => $request->kode_dir,
                'fieldcd' => $fieldcd,
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

    public function deleteDivisi(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('sys_divisi')->where('id','=',$request->id)->delete();
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

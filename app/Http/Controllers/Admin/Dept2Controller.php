<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Dept2Controller extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('sys_departemen')
        ->select(
            'sys_departemen.id',
            'sys_departemen.fieldnm',
            'sys_departemen.fieldcd',
            'sys_departemen.description',
            'sys_departemen.created_at',
            'sys_divisi.description as desc_divisi'
        )
        ->leftjoin('sys_divisi','sys_departemen.fieldcd_divisi','=','sys_divisi.fieldcd');
        $cari = $request->cari;

        if($cari !=''){
            $data = $data->where(function($data) use($cari){
                $data->where('sys_departemen.description','like',"%$cari%")
                ->orWhere('sys_departemen.fieldcd','like',"%$cari%")
                ->orWhere('sys_departemen.fieldnm','like',"%$cari%")
                ->orWhere('sys_divisi.description','like',"%$cari%");
            });
        }
        $data = $data->orderBy('sys_departemen.id','desc')->paginate(10);
        $data->appends($request->query());
        return view('admin.master.departement.index',compact('data','cari'));
    }

    public function showformdept()
    {
        $divisi = DB::table('sys_divisi')->get();
        return view('admin.master.departement.formdept',compact('divisi'));
    }

    public function storeDept(Request $request){
        $getNoDept = DB::table('sys_departemen')
                    ->select('fieldcd')
                    ->orderBy('fieldcd','desc')
                    ->first();
        $NoDept = $getNoDept->fieldcd+1;
        $validate = Validator::make($request->all(),[
            'kode_div' => 'required',
            'description' => 'required',
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
            DB::table('sys_departemen')->insert([
                'fieldnm' => 'DEPARTMENT',
                'fieldcd_divisi' => $request->kode_div,
                'fieldcd' => $NoDept,
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

    public function showEditDept(Request $request){
        $data = DB::table('sys_departemen')->where('id','=',$request->id)->first();
        $divisi = DB::table('sys_divisi')->get();

        $id = $request->id;
        $description = $data->description;
        $kode_div = $data->fieldcd_divisi;

        return view('admin.master.departement.formdept',compact('data',
            'description','kode_div','id','divisi'
        ));
    }

    public function saveEditDept(Request $request){
        $tmp = DB::table('sys_departemen')->where('id','=',$request->id)->first();
        $validate = Validator::make($request->all(),[
            'kode_div' => 'required',
            'description' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->all()
            ]);
        }

        $kode_desc = $request->description;
        $tmpdescription = $tmp->description;
        $fieldcd = $tmp->fieldcd;
        if($kode_desc != $tmpdescription){
            $getNoDept = DB::table('sys_departemen')
                        ->select('fieldcd')
                        ->orderBy('fieldcd','desc')
                        ->first();
            $fieldcd = $getNoDept->fieldcd+1; 
        }

        DB::beginTransaction();
        try {
            DB::table('sys_departemen')->where('id','=',$request->id)->update([
                'fieldnm' => 'DEPARTMENT',
                'fieldcd_divisi' => $request->kode_div,
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

    public function deleteDept(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_departemen')->where('id','=',$request->id)->delete();
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

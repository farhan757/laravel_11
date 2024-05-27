<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DivisiController extends Controller
{
    //
    public function index(Request $request){
        $data = DB::table('sys_sptcom')
                ->select(
                    'sys_sptcom.id',
                    'sys_sptcom.fieldnm',
                    'sys_sptcom.fieldcd',
                    'sys_sptcom.description',
                    'directo.fieldcd as directo_fieldcd',
                    'directo.descrip as directo_descrip',
                    'sys_sptcom.created_at'
                )
                ->join(DB::raw('(SELECT fieldnm, fieldcd, DESCRIPTION AS descrip FROM sys_sptcom WHERE sys_sptcom.fieldnm="DIREKTORAT") AS directo'), function ($join) {
                    $join->on(DB::raw('LEFT(sys_sptcom.fieldcd, 2)'), '=', 'directo.fieldcd');
                })
                ->where('sys_sptcom.fieldnm', '=', 'DIVISI')->where('sys_sptcom.fieldcd','!=','99999');
        $cari = $request->cari;
        if($cari != ""){
            $data = $data->where(function($data) use ($cari){
                            $data->where('sys_sptcom.fieldcd','like',"%$cari%")
                            ->orWhere('sys_sptcom.description','like',"%$cari%")
                            ->orWhere('directo.descrip','like',"%$cari%");
            });
        }
        $data = $data->orderBy('sys_sptcom.id','desc')->paginate(10);

        $data->appends($request->query());

        return view('admin.master.divisi.index',compact('data','cari'));
    }

    public function showformdivisi()
    {
        $direk = $this->direktorat();
        return view('admin.master.divisi.formdivisi',compact('direk'));
    }

    public function storeDivisi(Request $request){
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
            DB::table('sys_sptcom')->insert([
                'fieldnm' => 'DIVISI',
                'fieldcd' => $this->getNoSptCom('DIVISI',$request->kode_dir),
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
        $data = DB::table('sys_sptcom')->where('id','=',$request->id)->first();
        $direk = $this->direktorat();

        $id = $request->id;
        $description = $data->description;
        $kode_dir = substr($data->fieldcd,0,2);

        return view('admin.master.divisi.formdivisi',compact('data','direk',
            'description','kode_dir','id'
            ));
    }

    public function saveEditDivisi(Request $request){
        $tmp = DB::table('sys_sptcom')->where('id','=',$request->id)->first();
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

        $kode_dir = substr($request->kode_dir,0,2);
        $tmpkodedir = substr($tmp->fieldcd,0,2);
        $fieldcd = $tmp->fieldcd;
        if($kode_dir != $tmpkodedir){
            $fieldcd = $this->getNoSptCom('DIVISI',$request->kode_dir);
        }

        DB::beginTransaction();
        try {
            DB::table('sys_sptcom')->where('id','=',$request->id)->update([
                'fieldnm' => 'DIVISI',
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

    public function deleteDivisi(Request $request){
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

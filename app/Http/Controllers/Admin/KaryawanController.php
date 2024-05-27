<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KaryawanController extends Controller
{
    //

    public function formaddkaryawan(){
        $cabang = $this->cabang();
        $jabatan = $this->jabatan();
        $direk = $this->direktorat();
        return view('admin.master.karyawan.formkaryawan',compact('cabang','jabatan','direk'));
    }

    public function index(Request $request){
        $jabatan = DB::table('sys_jabatan');
        $data = DB::table('m_karyawan')
                ->leftJoin(DB::raw("(SELECT * FROM sys_sptcom WHERE sys_sptcom.fieldnm='BRANCH')  cb"),function($cb){
                    $cb->on('m_karyawan.kode_cab','=','cb.fieldcd');
                })
                ->leftJoin("sys_direktorat as dir",'m_karyawan.kode_direk','=','dir.fieldcd')
                ->leftJoin("sys_divisi as di",'m_karyawan.kode_div','=','di.fieldcd')
                ->leftJoin("sys_departemen as dp",'m_karyawan.kode_dept','=','dp.fieldcd')
                ->joinSub($jabatan->toSql(),'jabat',function($jabat){
                    $jabat->on('m_karyawan.kode_jabat','=','jabat.kode_jab');
                },null,null,'left');
        
        $cari = $request->cari;
        if($cari != ""){
            $data = $data->where('cb.description','like',"%$cari%")
                        ->orWhere('dp.description','like',"%$cari%")
                        ->orWhere('dir.description','like',"%$cari%")
                        ->orWhere('di.description','like',"%$cari%")
                        ->orWhere('jabat.nama_jab','like',"%$cari%")
                        ->orWhere('m_karyawan.nip','like',"%$cari%")
                        ->orWhere('m_karyawan.nama_lengkap','like',"%$cari%")
                        ->orWhere('m_karyawan.email','like',"%$cari%");
        }
        $data = $data->select([
                    DB::raw('cb.description as nama_cab, dp.description as nama_dept, di.description as nama_div , dir.description as nama_dir, jabat.nama_jab as nama_jabat'),
                    'm_karyawan.*'
                ])->paginate(10);
    
        $data->appends($request->query());

        return view('admin.master.karyawan.index',compact('data','cari'));
    }

    public function storeKaryawan(Request $request){
        
        $validate = Validator::make($request->all(),[
            'nip' => 'required|min:10|max:10|unique:m_karyawan',
            'email' => 'required|email|unique:m_karyawan',
            'username' => 'required|unique:m_karyawan',
            'nama_lengkap' => 'required',
            'kode_cab' => 'required',
            'kode_dir' => 'required',
            'kode_jabat' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->all()
            ]);
        }

        $password = Str::random(8);
        DB::beginTransaction();
        try {
            DB::table('m_karyawan')->insert([
                'nip' => $request->nip,
                'username' => $request->username,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'kode_cab' => trim($request->kode_cab),
                'kode_direk' => trim($request->kode_dir),
                'kode_dept' => trim($request->kode_dept),
                'kode_div' => trim($request->kode_div),
                'kode_jabat' => trim($request->kode_jabat),
                'password' => Hash::make($password),
                'user_by' => $this->GetUser()->nip,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['add']['success']." Password : $password",
                'data' => $password
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

    public function showEdit(Request $request){
        $data = DB::table('m_karyawan')->where('nip','=',$request->nip)->first();
        $cabang = $this->cabang();
        $jabatan = $this->jabatan();
        $direk = $this->direktorat();

        $nip = $data->nip;
        $username = $data->username;
        $nama_lengkap = $data->nama_lengkap;
        $email = $data->email;
        $kode_cab = trim($data->kode_cab);
        $kode_jabat = trim($data->kode_jabat);
        $kode_dir = trim($data->kode_direk);
        $kode_div = trim($data->kode_div);
        $kode_dept = trim($data->kode_dept);

        return view('admin.master.karyawan.formkaryawan',compact('data','cabang','jabatan','direk',
            'nip','username','nama_lengkap','email','kode_cab','kode_jabat','kode_dir',
            'kode_div','kode_dept'
            ));
    }

    public function saveEdit(Request $request){

        //return $request->all();
        $validate = Validator::make($request->all(),[
            'nama_lengkap' => 'required',
            'kode_cab' => 'required',
            'kode_dir' => 'required',
            'kode_jabat' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->all()
            ]);
        }

        $kode_dept = $request->kode_dept;
        $kode_dir = $request->kode_dir;
        $kode_div = $request->kode_div;
        DB::beginTransaction();
        try {
            DB::table('m_karyawan')->where('nip','=',$request->nip)->update([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'kode_cab' => $request->kode_cab,
                'kode_direk' => $kode_dir,
                'kode_dept' => $kode_dept,
                'kode_div' => $kode_div,
                'kode_jabat' => $request->kode_jabat,
                'user_by' => $this->GetUser()->nip,
                'updated_at' => Carbon::now()
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['save']['success']
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

    public function resetPassword(Request $request)
    {
        $password = Str::random(8);
        DB::beginTransaction();
        try {
            DB::table('m_karyawan')->where('nip','=',$request->nip)->update([
                'password' => Hash::make($password),
                'user_by' => $this->GetUser()->nip,
                'updated_at' => Carbon::now()
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['save']['success']." Password : $password",
                'data' => $password
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

    public function deleteKaryawan(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('m_karyawan')->where('nip','=',$request->nip)->delete();
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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthSSO\AuthSSOController;
use App\Listeners\BuildMenuListener;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //

    public function proseslogin(Request $request){
        
        //$logintipe = preg_match('/^(\d{6})\.(\d{3})$/',$request->unm_nip) ? 'nip' : 'username';
        $validate = Validator::make($request->all(),[
            'unm_nip' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()){
            return redirect()->route('login',['return' => $request->return])->with(['warning' => implode(', ',$validate->errors()->all())]); 
        }

        $user = Karyawan::where("nip",$request->unm_nip)->first();
        $login = false;
        if($user != null){
            if(Hash::check($request->password,$user->password)){
                $login = true;
            }
        }
        
        // if($user != null){
        //     $login = true;
        //     $authSSO = new AuthSSOController();
        //     $getInfoLogin = $authSSO->authLogin($request->unm_nip,$request->password);
        //     if($getInfoLogin != null){
        //         if($getInfoLogin->responcode != "00"){
        //             return redirect()->route('login',['return' => $request->return])->with(['warning' => $getInfoLogin->description]); 
        //         }
        //     }
        // }
        
        if($login)
        {
            $tmp = $this->getDetailUser("nip",$request->unm_nip);

            $this->attemptCustom($tmp);
            BuildMenu();
            MenuUser();
            
            if($this->checkLoginDevice()){
                $this->deleteDataSessionBynip();
                Session::flush();
                return redirect()->route('login',['return' => $request->return])->with(['warning' => 'Akun anda sedang login di device lain, mohon login ulang!']); 
            }else{
                $this->insertLogLogin();
            }

            return redirect()->intended('/home');
            // if($request->return == ""){
            //     return redirect()->route('home'); 
            // }else{
            //     return redirect($request->return);
            // }
        }else{
            return redirect()->route('login',['return' => $request->return])->with(['warning' => 'NIP / Password Salah']); 
        }
    }

    public function proseslogout(Request $request)
    {
        if(checkauthSSO()){
            $this->insertLogLogout();
            Session::flush();
            //session(['url.intended' => $request->url()]);
            return redirect()->route('login');
        }
    }

    public function attemptCustom($user){
        Session::put('user',$user);
    }

    public static function usersCustom(){
        return Session::get('user');
    }

    public static function checkAuthSSO(){
        return Session::get('user') ? true : false;
    }

    private function getDetailUser($kolom,$where)
    {
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
        },null,null,'left')
        ->where($kolom,$where)
        ->select([
            DB::raw('cb.description as nama_cab, dp.description as nama_dept, di.description as nama_div , dir.description as nama_dir, jabat.nama_jab as nama_jabat'),
            'm_karyawan.nip','m_karyawan.username','m_karyawan.nama_lengkap','m_karyawan.email','m_karyawan.no_hp',
            'm_karyawan.kode_cab','m_karyawan.kode_jabat','m_karyawan.kode_direk','m_karyawan.kode_div','m_karyawan.user_by',
            'm_karyawan.created_at','m_karyawan.updated_at','m_karyawan.kode_dept','m_karyawan.password'
            ])->first();

        return $data;
    }

    private function checkLoginDevice()
    {
        return DB::table('sessions')->where('user_id','=',usersCustom()->nip)->exists();
    }

    private function deleteDataSessionBynip()
    {
        DB::table('sessions')->where('user_id','=',usersCustom()->nip)->delete();
    }

    private function insertLogLogin()
    {
        DB::table('log_login')->insert([
            'user_id' => usersCustom()->nip,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'date_time' => Carbon::now(),
            'tipe' => 'LOGIN'
        ]);
    }

    private function insertLogLogout()
    {
        DB::table('log_login')->insert([
            'user_id' => usersCustom()->nip,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'date_time' => Carbon::now(),
            'tipe' => 'LOGOUT'
        ]);
    }
}

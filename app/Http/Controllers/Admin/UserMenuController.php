<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserMenuController extends Controller
{
    public function listUserMenu(Request $request)
    {
        $data = DB::table('m_karyawan')->select('nip','nama_lengkap');
        $cari = $request->cari;
        if($cari != ""){
            $data = $data->where('nip','like',"%$cari%")
            ->orWhere('nama_lengkap','like',"%$cari%");
        }

        $data = $data->orderBy('id','ASC')->paginate(10);
        $data->appends($request->query());

        $menus = DB::table('sys_menu')
            ->where('parent_id','=',0)->where('aktif','=',1)->where('jabat_or_user','=',1)
            ->select('sys_menu.*', DB::raw('null as contents'))
            ->get();
        $n = 0;
        foreach ($menus as $key => $value) {
            $menus[$n]->contents = DB::table('sys_menu')->where('aktif','=',1)->where('parent_id','=',$value->id)->get();
            $n++;
        }

        return view('admin.master.usermenu.index',compact('data','cari','menus'));
    }

    public function getusermenu(Request $request)
    {
        $data = DB::table('sys_menu_to_user')
            ->where('kode_user', '=', $request->kode_user)->get();

        return response()->json($data);
    }

    public function replaceusermenu($id, Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            DB::table('sys_menu_to_user')->where('kode_user', '=', $id)->delete();
            foreach($request->input('checkbox') as $key => $value) {
                DB::table('sys_menu_to_user')->insert([
                    'id_menu' => $value,
                    'kode_user' => $id,
                    'user_by' => usersCustom()->nip,
                    'created_at' => Carbon::now()
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 1,
                'message' => 'Menu berhasil diupdate'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 2,
                'message' => 'Menu gagal diupdate '. $th->getMessage()
            ]);
        }
    }
}

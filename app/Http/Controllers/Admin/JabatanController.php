<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JabatanController extends Controller
{
    //
    public function listJabatan(Request $request){
        $data = DB::table('sys_jabatan');
        
        $cari = $request->cari;

        if($cari != ""){
            $data = $data->where('nama_jab','like',"%$cari%");
        }

        $data = $data->orderBy('id','desc')->paginate(10);
        $data->appends($request->query());

        $menus = DB::table('sys_menu')
            ->where('parent_id', '=', 0)->where('aktif','=',1)
            ->select('sys_menu.*', DB::raw('null as contents'))
            ->get();
        $n = 0;
        foreach ($menus as $key => $value) {
            $menus[$n]->contents = DB::table('sys_menu')->where('aktif','=',1)->where('parent_id', '=', $value->id)->get();
            $n++;
        }

        return view('admin.master.jabatan.index',compact('data','cari','menus'));
    }

    public function getmenu(Request $request)
    {
        $data = DB::table('sys_menu_to_jabat')
            ->where('kode_jabat', '=', $request->kode_jab)->get();

        return response()->json($data);
    }

    public function replacemenu($id, Request $request)
    {

        DB::beginTransaction();
        try {
            //code...
            DB::table('sys_menu_to_jabat')->where('kode_jabat', '=', $id)->delete();
            foreach ($request->input('checkbox') as $key => $value) {
                DB::table('sys_menu_to_jabat')
                    ->insert([
                        'kode_jabat' => $id,
                        'id_menu' => $value,
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
            DB::rollback();
            return response()->json([
                'status' => 2,
                'message' => 'Menu gagal diupdate ' . $th->getMessage()
            ]);
        }
    }
}

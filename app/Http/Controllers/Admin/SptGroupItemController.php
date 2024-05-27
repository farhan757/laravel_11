<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SptGroupItemController extends Controller
{
    //
    public function listSptGroup(Request $request){
        $data = DB::table('sys_sptgroupitem');

        $cari = $request->cari;
        if($cari != ""){
            $data = $data->where(function($data) use($cari){
                $data->where('groupcd','like',"%$cari%")
                        ->orWhere('groupitem','like',"%$cari%")
                        ->orWhere('description','like',"%$cari%");
            });
        }
        $data = $data->orderBy('id','DESC')->paginate(10);
        $data->appends($request->query());

        return view('admin.master.sptgroupitem.index',compact('data','cari'));
    }

    public function showForm(Request $request){
        $sptgroup = DB::table('sys_sptgroup')->get();
        return view('admin.master.sptgroupitem.formsptgroupitem',compact('sptgroup'));
    }

    public function storeSptGroupItem(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitem')->insert([
                'groupcd' => $request->groupcd,
                'groupitem' => $request->groupitem,
                'description' => $request->description,
                'user_by' => usersCustom()->nip,
                'updated_at' => Carbon::now()
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['add']['success'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['add']['error']." data : ".$th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }

    public function showFormEdit(Request $request){
        $sptgroup = DB::table('sys_sptgroup')->get();
        $data = DB::table('sys_sptgroupitem')->where('groupitem',$request->groupitem)->first();
        $id = $data->id;
        return view('admin.master.sptgroupitem.formsptgroupitem',compact('sptgroup','data','id'));
    }
    
    public function storeEdit(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitem')->where('id',$request->id)->update([
                'groupcd' => $request->groupcd,
                'groupitem' => $request->groupitem,
                'description' => $request->description,
                'user_by' => usersCustom()->nip,
                'updated_at' => Carbon::now()
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['save']['success'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['save']['error']." data : ".$th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }

    public function deleteSptgroupItem(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitem')->where('id',$request->id)->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['delete']['success']
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['delete']['error']." data : ".$th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }
}

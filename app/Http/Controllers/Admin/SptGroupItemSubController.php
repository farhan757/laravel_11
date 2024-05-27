<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SptGroupItemSubController extends Controller
{
    //
    public function listSptGroupSub(Request $request){
        $data = DB::table('sys_sptgroupitem_sub');

        $cari = $request->cari;
        if($cari != ""){
            $data = $data->where(function($data) use($cari){
                $data->where('groupitem','like',"%$cari%")
                        ->orWhere('subitem','like',"%$cari%")
                        ->orWhere('description','like',"%$cari%");
            });
        }
        $data = $data->orderBy('id','DESC')->paginate(10);
        $data->appends($request->query());

        return view('admin.master.sptgroupitemsub.index',compact('data','cari'));
    }

    public function showForm(Request $request){
        $sptgroupitem = DB::table('sys_sptgroupitem')->get();
        $field_qty = 1;
        $field_amount = 1;

        return view('admin.master.sptgroupitemsub.formsptgroupitemsub',compact('sptgroupitem','field_qty','field_amount'));
    }

    public function storeSptGroupItemSub(Request $request){
        $flag_qty = 0; $flag_amount =0;
        if(isset($request->flag_qty)){
            $flag_qty = 1;
        }
        if(isset($request->flag_amount)){
            $flag_amount = 1;
        }
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitem_sub')->insert([
                'groupitem' => $request->groupitem,
                'subitem' => $request->subitem,
                'description' => $request->description,
                'user_by' => usersCustom()->nip,
                'flag_qty' => $flag_qty,
                'flag_amount' => $flag_amount,
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
        $sptgroupitem = DB::table('sys_sptgroupitem')->get();
        $data = DB::table('sys_sptgroupitem_sub')->where('subitem',$request->subitem)->first();
        $id = $data->id;
        $field_qty = $data->flag_qty;
        $field_amount = $data->flag_amount;
        return view('admin.master.sptgroupitemsub.formsptgroupitemsub',compact('sptgroupitem','data','id','field_qty','field_amount'));
    }
    
    public function storeEdit(Request $request){
        $flag_qty = 0; $flag_amount =0;
        if(isset($request->flag_qty)){
            $flag_qty = 1;
        }
        if(isset($request->flag_amount)){
            $flag_amount = 1;
        }
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitem_sub')->where('id',$request->id)->update([
                'groupitem' => $request->groupitem,
                'subitem' => $request->subitem,
                'description' => $request->description,
                'user_by' => usersCustom()->nip,
                'flag_qty' => $flag_qty,
                'flag_amount' => $flag_amount,
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

    public function deleteSptgroupItemSub(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitem_sub')->where('id',$request->id)->delete();
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

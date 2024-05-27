<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemsController extends Controller
{
    //
    public function listSptItems(Request $request){
        $data = DB::table('sys_sptgroupitemsub_to_items');

        $cari = $request->cari;
        if($cari != ""){
            $data = $data->where(function($data) use($cari){
                $data->where('subitem','like',"%$cari%")
                        ->orWhere('itemcd','like',"%$cari%")
                        ->orWhere('Description','like',"%$cari%");
            });
        }
        $data = $data->orderBy('id','DESC')->paginate(10);
        $data->appends($request->query());

        return view('admin.master.sptitem.index',compact('data','cari'));
    }

    public function showForm(Request $request){
        $sptgroupitemsub = DB::table('sys_sptgroupitem_sub')->get();
        return view('admin.master.sptitem.formsptitem',compact('sptgroupitemsub'));
    }

    public function storeSptItem(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitemsub_to_items')->insert([
                'subitem' => $request->subitem,
                'itemcd' => $request->itemcd,
                'Description' => $request->description,
                'user_by' => usersCustom()->nip,
                'updatedt' => Carbon::now()
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
        $sptgroupitemsub = DB::table('sys_sptgroupitem_sub')->get();
        $data = DB::table('sys_sptgroupitemsub_to_items')->where('itemcd',$request->itemcd)->first();
        $id = $data->id;
        return view('admin.master.sptitem.formsptitem',compact('sptgroupitemsub','data','id'));
    }
    
    public function storeEdit(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitemsub_to_items')->where('id',$request->id)->update([
                'subitem' => $request->subitem,
                'itemcd' => $request->itemcd,
                'Description' => $request->description,
                'user_by' => usersCustom()->nip,
                'updatedt' => Carbon::now()
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

    public function deleteSptItem(Request $request){
        DB::beginTransaction();
        try {
            DB::table('sys_sptgroupitemsub_to_items')->where('id',$request->id)->delete();
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SptComController extends Controller
{
    //
    public function index(Request $request)
    {
        # code...
        $data = DB::table('sys_sptcom');

        $cari = $request->cari;
        if($cari != '')
        {
            $data = $data->where('fieldnm','like',"%$cari%")
                    ->orWhere('fieldcd','like',"%$cari%")
                    ->orWhere('description','like',"%$request->description%");
        }

        if($request->form == "add"){
            $data = $data->groupBy('fieldnm')->get();
            return view('admin.master.sptcom.form')->with('data',$data);
        }

        if($request->form == "edit"){
            $first = DB::table('sys_sptcom')->where('id','=',$request->id)->first();
            $data = $data->groupBy('fieldnm')->get();
            return view('admin.master.sptcom.form')->with([
                'data' => $data,
                'fieldcd' => $first->fieldcd,
                'fieldnm' => $first->fieldnm,
                'description' => $first->description,
                'id' => $request->id
            ]);
        }

        $data = $data->paginate(10);
        $data->appends($request->query());
        return view('admin.master.sptcom.index')->with([
            'data' => $data,
            'cari' => $request->cari
        ]);
    }

    public function add(Request $request)
    {
        # code...

        Validator::make($request->all(),[
            'fieldcd' => 'required|string',
            'description' => 'required|string',
        ]);

        DB::beginTransaction();
        try{
            DB::table('sys_sptcom')->insert([
                'fieldcd' => $request->fieldcd,
                'description' => $request->description,
                'fieldnm' => $request->fieldnm,
                'user_by' => usersCustom()->nip,
                'created_at' => Carbon::now()
            ]);
            DB::commit();

            return redirect()->back()->with('success','Success add params');
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function saveparam(Request $request)
    {
        # code...
        DB::beginTransaction();
        try{
            DB::table('sys_sptcom')->where('id','=',$request->id)->update([
                'fieldcd' => $request->fieldcd,
                'description' => $request->description,
                'fieldnm' => $request->fieldnm,
                'user_by' => usersCustom()->nip,
                'created_at' => Carbon::now()
            ]);
            DB::commit();

            return redirect()->back()->with('success','success edit param');
        }catch(Exception $e){
            DB::rollback();

            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function deleteparam(Request $request)
    {
        # code...
        DB::beginTransaction();
        try{
            DB::table('sys_sptcom')->where('id','=',$request->id)->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['delete']['success']
            ]);
        }catch(Exception $th){
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['delete']['error']." data : ".$th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }
}

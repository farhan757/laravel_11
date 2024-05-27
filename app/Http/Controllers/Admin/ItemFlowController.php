<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemFlowController extends Controller
{
    //
    public function listItemFlow(Request $request)
    {
        $data = DB::table('sys_flow_jenispermit')
            ->join('sys_sptgroupitem_sub', 'sys_flow_jenispermit.itemgrp', '=', 'sys_sptgroupitem_sub.subitem');

        $cari = $request->cari;
        if ($cari != "") {
            $data = $data->where(function ($data) use ($cari) {
                $data->where('sys_sptgroupitem_sub.description', 'like', "%$cari%")
                    ->orWhere('sys_flow_jenispermit.nama_flow', 'like', "%$cari%");
            });
        }

        $data = $data->select([
            'sys_flow_jenispermit.*', DB::raw('max(sys_flow_jenispermit.urutan) as jml_step, sys_sptgroupitem_sub.description as desc_subitem')
        ])
            ->groupBy('sys_flow_jenispermit.itemgrp')->orderBy('id', 'desc')->paginate(10);
        $data->appends($request->query());

        return view('admin.master.itemflow.index', compact('data', 'cari'));
    }

    public function showForm(Request $request)
    {
        $sptgroupitemsub = DB::table('sys_sptgroupitem_sub')
            ->leftJoin('sys_flow_jenispermit', 'sys_sptgroupitem_sub.subitem', '=', 'sys_flow_jenispermit.itemgrp')
            ->whereNull('sys_flow_jenispermit.itemgrp')
            //->groupBy('sys_flow_jenispermit.itemgrp')
            ->get(['sys_sptgroupitem_sub.*']);
        $itemflow = DB::table('sys_item_flow')->get();
        return view('admin.master.itemflow.formitemflow', compact('sptgroupitemsub', 'itemflow'));
    }

    public function storeFlowItem(Request $request)
    {
        $toInsert = [];
        $urutan = 0;
        foreach ($request->toitemflow as $key => $value) {
            $urutan++;
            try {
                $toInsert[] = [
                    'urutan' => $urutan,
                    'kodeitem_flow' => $request->toitemflow[$key],
                    'itemgrp' => $request->subitem,
                    'nama_flow' => $request->nama_flow,
                    'user_by' => usersCustom()->nip,
                    'created_at' => Carbon::now()
                ];
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => $this->message['default']['add']['error'] . " data : " . $th->getMessage(),
                    'data' => $th->getMessage()
                ]);
            }
        }

        DB::beginTransaction();
        try {
            DB::table('sys_flow_jenispermit')->insert($toInsert);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['add']['success'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['add']['error'] . " data : " . $th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }

    public function showFormEdit(Request $request)
    {
        $itemgrp = $request->itemgrp;
        $data = DB::table('sys_flow_jenispermit')
            ->join('sys_item_flow', 'sys_flow_jenispermit.kodeitem_flow', '=', 'sys_item_flow.kodeitem_flow')
            ->where('itemgrp', '=', $itemgrp)->get([
                'sys_flow_jenispermit.*', 'sys_item_flow.namaitem_flow'
            ]);
        $sptgroupitemsub = DB::table('sys_sptgroupitem_sub')
            ->leftJoin('sys_flow_jenispermit', 'sys_sptgroupitem_sub.subitem', '=', 'sys_flow_jenispermit.itemgrp')
            ->whereNull('sys_flow_jenispermit.itemgrp')
            ->groupBy('sys_flow_jenispermit.itemgrp')
            ->get(['sys_sptgroupitem_sub.*']);

        $sptgroupitemsubexist = DB::table('sys_sptgroupitem_sub')
            ->leftJoin(DB::raw('(SELECT * FROM sys_flow_jenispermit WHERE sys_flow_jenispermit.itemgrp != "' . $itemgrp . '") sys_flow_jenispermit'), function ($join) {
                $join->on('sys_sptgroupitem_sub.subitem', '=', 'sys_flow_jenispermit.itemgrp');
            })
            ->whereNull('sys_flow_jenispermit.itemgrp')
            ->groupBy('sys_flow_jenispermit.itemgrp')
            ->get(['sys_sptgroupitem_sub.*']);

        $itemflow = DB::table('sys_item_flow')
            ->leftJoin(DB::raw('(SELECT * FROM sys_flow_jenispermit WHERE sys_flow_jenispermit.itemgrp = "' . $itemgrp . '") sys_flow_jenispermit'), function ($join) {
                $join->on('sys_item_flow.kodeitem_flow', '=', 'sys_flow_jenispermit.kodeitem_flow');
            })
            ->whereNull('sys_flow_jenispermit.kodeitem_flow')
            ->get([
                'sys_item_flow.*'
            ]);
        return view('admin.master.itemflow.formitemflow', compact('sptgroupitemsub', 'sptgroupitemsubexist', 'itemflow', 'itemgrp', 'data'));
    }

    public function storeEditFlowItem(Request $request)
    {
        //return $request->all();
        $itemgrp = $request->itemgrp;
        $toInsert = [];
        $urutan = 0;
        foreach ($request->toitemflow as $key => $value) {
            $urutan++;
            try {
                $toInsert[] = [
                    'urutan' => $urutan,
                    'kodeitem_flow' => $request->toitemflow[$key],
                    'itemgrp' => $request->subitem,
                    'nama_flow' => $request->nama_flow,
                    'user_by' => usersCustom()->nip,
                    'created_at' => Carbon::now()
                ];
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => $this->message['default']['save']['error'] . " data : " . $th->getMessage(),
                    'data' => $th->getMessage()
                ]);
            }
        }

        DB::beginTransaction();
        try {
            DB::table('sys_flow_jenispermit')->where('itemgrp','=',$itemgrp)->delete();
            DB::table('sys_flow_jenispermit')->insert($toInsert);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => $this->message['default']['save']['success'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $this->message['default']['save']['error'] . " data : " . $th->getMessage(),
                'data' => $th->getMessage()
            ]);
        }
    }

    public function deleteItemFlow(Request $request){
        $itemgrp = $request->id;
        DB::beginTransaction();
        try {
            DB::table('sys_flow_jenispermit')->where('itemgrp','=',$itemgrp)->delete();
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

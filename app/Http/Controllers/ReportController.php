<?php

namespace App\Http\Controllers;

use App\Exports\DataExp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    //
    public $field = [
        't_request.kode_req' => 'Request Code',
        't_request.date_req' => 'Request Date',
        't_request.kode_result' => 'Result Code',
        't_request.groupcd' => 'Group Code',
        't_requestitem.itemgrp' => 'Item Group',
        'sys_sptgroupitem_sub.description' => 'Nama Item Group',
        't_requestitem.itemcd' => 'Item Code',
        't_requestitem.desc_item' => 'Nama Item Code',
        't_requestitem.qty' => 'Quantity',
        't_requestitem.amount' => 'Amount',
        't_requestitem.ttlamount' => 'Total Amount',
        't_requestitem_selesai.amount_pakai' => 'Amount Terpakai Kasbon',
        't_requestitem_selesai.amount_sisa' => 'Amount Sisa Kasbon',
        't_flow_requestitem.desc_dept' => 'Departemen',
        'init' => 'Initiator',
        'notes_init' => 'Notes Initiator',
        'date_init' => 'Date Initiator',
        'knit' => 'Atasan Initiator',
        'notes_knit' => 'Notes Atasan Initiator',
        'date_knit' => 'Date Atasan Initiator',
        'ssit' => 'Staff IT',
        'notes_ssit' => 'Notes Staff IT',
        'date_ssit' => 'Date Staff IT',
        'kait' => 'Atasan IT',
        'notes_kait' => 'Notes Atasan IT',
        'date_kait' => 'Date Atasan IT',
        'shrd' => 'Staff HRD',
        'notes_shrd' => 'Notes Staff HRD',
        'date_shrd' => 'Date Staff HRD',
        'khrd' => 'Atasan HRD',
        'notes_khrd' => 'Notes Atasan HRD',
        'date_khrd' => 'Date Atasan HRD',
        'sfam' => 'Staff FAM',
        'notes_sfam' => 'Notes Staff FAM',
        'date_sfam' => 'Date Staff FAM',
        'kfam' => 'Atasan FAM',
        'notes_kfam' => 'Notes Atasan FAM',
        'date_kfam' => 'Date Atasan FAM',

        'penn' => 'Staff Penyelesaian Kasbon',
        'notes_penn' => 'Notes Staff Penyelesaian Kasbon',
        'date_penn' => 'Date Staff Penyelesaian Kasbon',
        'skbn' => 'Staff FAM Terima Penyelesaian Kasbon',
        'notes_skbn' => 'Notes Staff FAM Terima Penyelesaian Kasbon',
        'date_skbn' => 'Date Staff FAM Terima Penyelesaian Kasbon',
        'kkbn' => 'Atasan FAM Penyelesaian Kasbon',
        'notes_kkbn' => 'Notes Atasan FAM Penyelesaian Kasbon',
        'date_kkbn' => 'Date Atasan FAM Penyelesaian Kasbon'
    ];

    public function index(Request $request)
    {
        $field = $this->field;
        $groupcd = $this->sptgroup();
        $itemgrp = DB::table('sys_sptgroupitem_sub')->get();
        $dept = DB::table('sys_departemen')->get();
        return view('formreport', compact('field', 'groupcd', 'itemgrp','dept'));
    }

    public function showreport(Request $request)
    {
        $rs = DB::table('t_request');

        if (isset($request->from) && isset($request->to)) {
            $rs = $rs->whereBetween(DB::raw("DATE_FORMAT(t_request.date_req,'%Y-%m-%d')"), [$request->from, $request->to]);
        }

        if (isset($request->groupcd) && isset($request->itemgrp)) {
            $rs = $rs->where(function ($rs) use ($request) {
                if ($request->oper == "or") {
                    $rs = $rs->where('t_request.groupcd', '=', $request->groupcd)
                        ->orWhereIn('t_requestitem.itemgrp', $request->itemgrp);
                } else {
                    $rs = $rs->where('t_request.groupcd', '=', $request->groupcd)
                        ->whereIn('t_requestitem.itemgrp', $request->itemgrp);
                }
            });
        } else if (isset($request->groupcd)) {
            $rs = $rs->where('t_request.groupcd', '=', $request->groupcd);
        } else if (isset($request->itemgrp)) {
            $rs = $rs->whereIn('t_requestitem.itemgrp', $request->itemgrp);
        }
        
        if(isset($request->dept)){
            $rs = $rs->whereIn('t_flow_requestitem.kode_dept',$request->dept);
        }

        $rs = $rs->join(DB::raw('(SELECT t_flow_requestitem.*, m_karyawan.nama_lengkap, m_karyawan.kode_dept, sys_departemen.description as desc_dept, CONCAT(t_flow_requestitem.user_by, "-", m_karyawan.nama_lengkap) AS userby FROM t_flow_requestitem JOIN m_karyawan ON t_flow_requestitem.user_by = m_karyawan.nip JOIN sys_departemen ON sys_departemen.fieldcd=m_karyawan.kode_dept) AS t_flow_requestitem'), function ($join) {
            $join->on('t_request.kode_req', '=', 't_flow_requestitem.kode_req');
        })
            ->join('t_requestitem', 't_request.kode_req', '=', 't_requestitem.kode_req')
            ->join('sys_sptgroupitem_sub', 't_requestitem.itemgrp', '=', 'sys_sptgroupitem_sub.subitem')
            ->leftJoin('t_requestitem_selesai', 't_request.kode_req', '=', 't_requestitem_selesai.kode_req')
            ->groupBy('t_request.kode_req', 't_requestitem.id');

        $lopkey = $this->field;
        if (isset($request->keyselect)) {
            $lopkey = $request->keyselect;
        }

        $keyfield = [];
        $getKeyData = [];
        foreach ($lopkey as $key => $items) {

            $item = $key;
            if (isset($request->keyselect)) {
                $item = $items;
            }

            if (Str::contains($item, 't_request') || Str::contains($item,'sys_sptgroupitem_sub') || Str::contains($item,'t_requestitem_selesai')
               || Str::contains($item,'t_flow_requestitem')) {
                
                if(Str::contains($item,'ttlamount')){
                    $rs = $rs->addSelect(DB::raw('(t_requestitem.amount*t_requestitem.qty) as ttlamount'));
                }else{
                    $rs = $rs->addSelect($item);
                }
            }
            switch ($item) {
                case 'init':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "INIT" THEN t_flow_requestitem.userby END) AS init'));
                    break;
                case 'notes_init':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "INIT" THEN t_flow_requestitem.notes END) AS notes_init'));
                    break;
                case 'date_init':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "INIT" THEN t_flow_requestitem.created_at END) AS date_init'));
                    break;
                case 'knit':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KNIT" THEN t_flow_requestitem.userby END) AS knit'));
                    break;
                case 'notes_knit':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KNIT" THEN t_flow_requestitem.notes END) AS notes_knit'));
                    break;
                case 'date_knit':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KNIT" THEN t_flow_requestitem.created_at END) AS date_knit'));
                    break;
                case 'ssit':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SSIT" THEN t_flow_requestitem.userby END) AS ssit'));
                    break;
                case 'notes_ssit':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SSIT" THEN t_flow_requestitem.notes END) AS notes_ssit'));
                    break;
                case 'date_ssit':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SSIT" THEN t_flow_requestitem.created_at END) AS date_ssit'));
                    break;
                case 'kait':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KAIT" THEN t_flow_requestitem.userby END) AS kait'));
                    break;
                case 'notes_kait':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KAIT" THEN t_flow_requestitem.notes END) AS notes_kait'));
                    break;
                case 'date_kait':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KAIT" THEN t_flow_requestitem.created_at END) AS date_kait'));
                    break;
                case 'shrd':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SHRD" THEN t_flow_requestitem.userby END) AS shrd'));
                    break;
                case 'notes_shrd':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SHRD" THEN t_flow_requestitem.notes END) AS notes_shrd'));
                    break;
                case 'date_shrd':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SHRD" THEN t_flow_requestitem.created_at END) AS date_shrd'));
                    break;
                case 'khrd':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KHRD" THEN t_flow_requestitem.userby END) AS khrd'));
                    break;
                case 'notes_khrd':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KHRD" THEN t_flow_requestitem.notes END) AS notes_khrd'));
                    break;
                case 'date_khrd':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KHRD" THEN t_flow_requestitem.created_at END) AS date_khrd'));
                    break;
                case 'sfam':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SFAM" THEN t_flow_requestitem.userby END) AS sfam'));
                    break;
                case 'notes_sfam':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SFAM" THEN t_flow_requestitem.notes END) AS notes_sfam'));
                    break;
                case 'date_sfam':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SFAM" THEN t_flow_requestitem.created_at END) AS date_sfam'));
                    break;
                case 'kfam':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KFAM" THEN t_flow_requestitem.userby END) AS kfam'));
                    break;
                case 'notes_kfam':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KFAM" THEN t_flow_requestitem.notes END) AS notes_kfam'));
                    break;
                case 'date_kfam':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KFAM" THEN t_flow_requestitem.created_at END) AS date_kfam'));
                    break;

                case 'penn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "PENN" THEN t_flow_requestitem.userby END) AS penn'));
                    break;
                case 'notes_penn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "PENN" THEN t_flow_requestitem.notes END) AS notes_penn'));
                    break;
                case 'date_penn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "PENN" THEN t_flow_requestitem.created_at END) AS date_penn'));
                    break;
                case 'skbn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SKBN" THEN t_flow_requestitem.userby END) AS skbn'));
                    break;
                case 'notes_skbn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SKBN" THEN t_flow_requestitem.notes END) AS notes_skbn'));
                    break;
                case 'date_skbn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "SKBN" THEN t_flow_requestitem.created_at END) AS date_skbn'));
                    break;
                case 'kkbn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KKBN" THEN t_flow_requestitem.userby END) AS kkbn'));
                    break;
                case 'notes_kkbn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KKBN" THEN t_flow_requestitem.notes END) AS notes_kkbn'));
                    break;
                case 'date_kkbn':
                    $rs = $rs->addSelect(DB::raw('MAX(CASE WHEN t_flow_requestitem.kodeitem_flow = "KKBN" THEN t_flow_requestitem.created_at END) AS date_kkbn'));
                    break;
            }
            array_push($keyfield, $this->field[$item]);
            array_push($getKeyData, str_replace("t_requestitem.", "", str_replace("t_request.", "", str_replace("sys_sptgroupitem_sub.","",str_replace("t_requestitem_selesai.","",$item)))));
        }
        $data = [
            'data' => $rs->get(),
            'field' => $keyfield
        ];
        
        try {
            return Excel::download(new DataExp($data),"$request->nmfile.xlsx");
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        // $rs = $rs->get()->toArray();
        // $tmpdata = json_decode(json_encode($rs), true);
        
        // return view('viewreport', compact('tmpdata', 'keyfield', 'getKeyData'));
    }
}

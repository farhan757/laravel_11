<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //
    public $DiruploadImg = "/uploadfile/";

    public $message = array(
        'default' => array(
            'add' => array(
                'success' => 'Data berhasil ditambahkan',
                'error' => 'Gagal menambahkan data'
            ),
            'save' => array(
                'success' => 'Data berhasil disimpan',
                'error' => 'Gagal menyimpan data'
            ),
            'delete' => array(
                'success' => 'Data berhasil dihapus',
                'error' => 'Gagal menhapus data'
            ),
            'status' => array(
                'success' => 'Berhasil update status',
                'error' => 'Update status gagal'
            ),
        ),
    );

    public function cabang()
    {
        return DB::table('sys_sptcom')->where('fieldnm', '=', 'BRANCH')->get([
            DB::raw('sys_sptcom.description as nama_cab, sys_sptcom.fieldcd as kode_cab')
        ]);
    }

    public function jabatan()
    {
        return DB::table('sys_jabatan')->get();
    }

    public function departemen(Request $request)
    {
        $data = DB::table('sys_departemen');

        if ($request->prefix != null) {
            $data = $data->where('fieldcd_divisi', $request->prefix);
        }

        $data = $data->orderBy('sys_departemen.fieldcd', 'ASC')->get([
            DB::raw('sys_departemen.description as nama_dep, sys_departemen.fieldcd as kode_dep')
        ]);
        return $data;
    }

    public function divisi(Request $request)
    {
        $data = DB::table('sys_divisi');

        if ($request->prefixx != null) {
            $data = $data->where('fieldcd_direk', $request->prefixx);
        }

        $data = $data->orderBy('sys_divisi.fieldcd', 'ASC')->get([
            DB::raw('sys_divisi.description as nama_div, sys_divisi.fieldcd as kode_div')
        ]);
        return $data;
    }

    public function direktorat($prefix = null)
    {
        $data = DB::table('sys_direktorat');

        $data = $data->orderBy('sys_direktorat.fieldcd', 'ASC')->get([
            DB::raw('sys_direktorat.description as nama_dir, sys_direktorat.fieldcd as kode_dir')
        ]);
        return $data;
    }



    public function GetUser()
    {
        return usersCustom(); //Auth::guard('karyawan')->user();
    }

    public function sptgroup()
    {
        return DB::table('sys_sptgroup')->get();
    }

    public function getspgroupItem($groupcd)
    {
        return DB::table('sys_sptgroup')->where('groupcd', $groupcd)->get();
    }

    public function getItemByitemgrp($subitem)
    {
        return DB::table('sys_sptgroupitem_sub')->where('subitem', $subitem)->get();
    }

    public function getItemgrp($groupcd)
    {
        $data = DB::table('sys_sptgroupitem')->where('groupitem', '=', $groupcd)->get([
            'groupitem', 'description'
        ]);
        return $data;
    }
}

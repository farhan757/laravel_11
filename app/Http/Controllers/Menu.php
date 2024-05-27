<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Menu extends Controller
{
    //

    public function activeMenu($uri = '')
    {
        $active = '';
        if (request()->is(request()->segment(1) . '/' . $uri . '/*') || request()->is(request()->segment(1) . '/' . $uri) || request()->is($uri) || request()->routeIs($uri . '*')) {
            $active = 'active';
        }
        return $active;
    }

    public function activeTreeMenu($menu_id)
    {
        $active = '';
        $menu = DB::table('sys_menu')->where('parent_id', '=', $menu_id)->get();
        foreach ($menu as $cek) {
            if (request()->routeIs($cek->nama_route . '*')) {
                $active = 'active';
                break;
            }
        }
        return $active;
    }

    public function CreateMenu(){
        $menu = $this->BuildMenu();
        Session::put('menu', $menu);
    }

    public function BuildMenu($parentId = 0)
    {
        $user = $this->GetUser();
        $menu = [];
        $menuItem = DB::table('sys_menu');
        $menuItem = $menuItem->join('sys_menu_to_jabat','sys_menu.id','=','sys_menu_to_jabat.id_menu')
        ->where('sys_menu_to_jabat.kode_jabat','=',$user->kode_jabat);

        $menuItem = $menuItem->where('parent_id', $parentId)->where('aktif', '=', 1)->where('nama_app', '=', 'REIM')->get('sys_menu.*');
        foreach ($menuItem as $item) {
            $children = $this->BuildMenu($item->id);
            if ($children->isNotEmpty()) {
                $item->child = $children;
            }
            $menu[] = $item;
        }

        return collect($menu);
    }

    public function CreateMenuUser()
    {
        $menuuser = $this->MenuUser();
        Session::put('menuuser',$menuuser);
    }

    public function MenuUser($parentId = 0)
    {
        $user = $this->GetUser();
        $menu = [];
        $menuItem = DB::table('sys_menu');
        $menuItem = $menuItem->join('sys_menu_to_user','sys_menu.id','=','sys_menu_to_user.id_menu')
        ->where('sys_menu_to_user.kode_user','=',$user->nip);

        $menuItem = $menuItem->where('parent_id', $parentId)->where('aktif', '=',1)->where('nama_app', '=', 'REIM')->get('sys_menu.*');

        foreach ($menuItem as $item) {
            $children = $this->MenuUser($item->id);
            if($children->isNotEmpty()) {
                $item->child = $children;
            }
            $menu[] = $item;
        }
        return collect($menu);
    }
}

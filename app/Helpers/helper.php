<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Menu;

if(!function_exists('checkauthSSO')){
    function checkauthSSO() : bool
    {
        return AuthController::checkAuthSSO();
    }
}

if(!function_exists('usersCustom')){
    function usersCustom()
    {
        return AuthController::usersCustom();
    }
}

if(!function_exists('BuildMenu')){
    function BuildMenu()
    {
        $menu = new Menu();
        return $menu->CreateMenu();
    }
}

if(!function_exists('MenuUser')){
    function MenuUser()
    {
        $menu = new Menu();
        return $menu->CreateMenuUser();
    }
}

if(!function_exists('ToRupiah')){
    function ToRupiah($param1,$currency="Rp ")
    {
        $rounded_number = round($param1, 2);
        $formatted_number = number_format($rounded_number, 2, '.', ',');
        $rupiah_format = $currency . $formatted_number;
        return trim($rupiah_format);
    }
}


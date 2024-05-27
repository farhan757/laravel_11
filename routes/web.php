<?php

use App\Http\Controllers\Admin\Dept2Controller;
use App\Http\Controllers\Admin\Direktorat2Controller;
use App\Http\Controllers\Admin\Divisi2Controller;
use App\Http\Controllers\Admin\ItemFlowController;
use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\SptComController;
use App\Http\Controllers\Admin\SptGroupItemController;
use App\Http\Controllers\Admin\SptGroupItemSubController;
use App\Http\Controllers\Admin\UserMenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthSSO\AuthSSOController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RedisController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::post('/message/send', [ChangePasswordController::class, 'sendMessage'])->name('sendMessage');
Route::get('/pubres', [RedisController::class, 'publish'])->name('publish');
Route::get('/pubress', [RedisController::class, 'testlain']);

Route::get('/', function (Request $request) {
    if (checkauthSSO()) {
        return redirect()->route('home');
    } else {
        return view('auth.login');
    }
})->name('login');

Route::post('/ping', [AuthSSOController::class, 'pingApiSSO'])->name('pingsso');
Route::post('/proseslogin', [AuthController::class, 'proseslogin'])->name('post.login');
// akhir untuk login

// untuk logout
Route::get('/proseslogout', [AuthController::class, 'proseslogout']);
Route::post('/proseslogout', [AuthController::class, 'proseslogout'])->name('proseslogout');
// akhir untuk logout

Route::get('getDivisi', [Controller::class, 'divisi'])->name('getdivisi');
Route::get('getDept', [Controller::class, 'departemen'])->name('getdept');

Route::middleware(['authssocustom', 'check.unique.cookie'])->group(function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/formchangepass', [ChangePasswordController::class, 'index'])->name('form.changepass');
    Route::post('/changepassword', [ChangePasswordController::class, 'changepassword'])->name('changepassword');

    Route::post('getmenu', [JabatanController::class, 'getmenu'])->name('getmenu');
    Route::post('getusermenu', [UserMenuController::class, 'getusermenu'])->name('getusermenu');

    Route::group(['prefix' => 'master'], function () {
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.list');
        Route::post('/storeKaryawan', [KaryawanController::class, 'storeKaryawan'])->name('karyawan.store');
        Route::get('/showEdit', [KaryawanController::class, 'showEdit'])->name('karyawan.showEdit');
        Route::get('/karyawan/formadd', [KaryawanController::class, 'formaddkaryawan'])->name('karyawan.formadd');
        Route::post('/saveEdit', [KaryawanController::class, 'saveEdit'])->name('karyawan.edit');
        Route::get('/resetPassword', [KaryawanController::class, 'resetPassword'])->name('karyawan.resetpassword');
        Route::get('/deleteKaryawan', [KaryawanController::class, 'deleteKaryawan'])->name('karyawan.delete');

        // add new departement
        Route::get('/departement', [Dept2Controller::class, 'index'])->name('department.list');
        Route::get('/showformdept', [Dept2Controller::class, 'showformdept'])->name('department.showformdept');
        Route::post('/storeDept', [Dept2Controller::class, 'storeDept'])->name('department.storeDept');
        Route::get('/showEditDept', [Dept2Controller::class, 'showEditDept'])->name('department.showEditDept');
        Route::post('saveEditDept', [Dept2Controller::class, 'saveEditDept'])->name('department.saveEditDept');
        Route::get('/deleteDept', [Dept2Controller::class, 'deleteDept'])->name('department.deleteDept');
        // =========

        // add new divisi
        Route::get('/divisi', [Divisi2Controller::class, 'index'])->name('divisi.list');
        Route::get('/showformdivisi', [Divisi2Controller::class, 'showformdivisi'])->name('divisi.showformdivisi');
        Route::post('/storeDivisi', [Divisi2Controller::class, 'storeDivisi'])->name('divisi.storeDivisi');
        Route::get('/showEditDivisi', [Divisi2Controller::class, 'showEditDivisi'])->name('divisi.showEditDivisi');
        Route::post('/saveEditDivisi', [Divisi2Controller::class, 'saveEditDivisi'])->name('divisi.saveEditDivisi');
        Route::get('/deleteDivisi', [Divisi2Controller::class, 'deleteDivisi'])->name('divisi.deleteDivisi');
        // ==============

        // addd new direktorat
        Route::get('/direktorat',[Direktorat2Controller::class,'index'])->name('direktorat.list');
        Route::get('/showformdirek',[Direktorat2Controller::class,'showformdirek'])->name('direktorat.showformdirek');
        Route::post('/storeDirek',[Direktorat2Controller::class,'storeDirek'])->name('direktorat.storeDirek');
        Route::get('/showEditDirek',[Direktorat2Controller::class,'showEditDirek'])->name('direktorat.showEditDirek');
        Route::post('/saveEditDirek',[Direktorat2Controller::class,'saveEditDirek'])->name('direktorat.saveEditDirek');
        Route::get('/deleteDirek',[Direktorat2Controller::class,'deleteDirek'])->name('direktorat.deleteDirek');
        // ==============

        Route::get('/sptcom_parameter',[SptComController::class,'index'])->name('sptcom.list');
        Route::post('/sptcom_add',[SptComController::class,'add'])->name('sptcom.add');
        Route::post('/sptcom_saveparam',[SptComController::class,'saveparam'])->name('sptcom.saveparam');
        Route::get('/sptcom_deleteparam',[SptComController::class,'deleteparam'])->name('sptcom.deleteparam');

        Route::get('/sptgroupitem', [SptGroupItemController::class, 'listSptGroup'])->name('sptgroupitem.listSptGroup');
        Route::get('/showFormsptgroupitem', [SptGroupItemController::class, 'showForm'])->name('sptgroupitem.showForm');
        Route::post('/storeSptGroupItem', [SptGroupItemController::class, 'storeSptGroupItem'])->name('sptgroupitem.storeSptGroupItem');
        Route::get('/showFormEditsptgroupitem', [SptGroupItemController::class, 'showFormEdit'])->name('sptgroupitem.showFormEdit');
        Route::post('/storeEditsptrgroupitem', [SptGroupItemController::class, 'storeEdit'])->name('sptgroupitem.storeEdit');
        Route::get('/deleteSptgroupItem', [SptGroupItemController::class, 'deleteSptgroupItem'])->name('sptgroupitem.deleteSptgroupItem');

        Route::get('/sptgroupitemsub', [SptGroupItemSubController::class, 'listSptGroupSub'])->name('sptgroupitemsub.listSptGroupSub');
        Route::get('/showFormsptgroupitemsub', [SptGroupItemSubController::class, 'showForm'])->name('sptgroupitemsub.showForm');
        Route::post('/storeSptGroupItemsub', [SptGroupItemSubController::class, 'storeSptGroupItemSub'])->name('sptgroupitemsub.storeSptGroupItemSub');
        Route::get('/showFormEditsptgroupitemsub', [SptGroupItemSubController::class, 'showFormEdit'])->name('sptgroupitemsub.showFormEdit');
        Route::post('/storeEditsptrgroupitemsub', [SptGroupItemSubController::class, 'storeEdit'])->name('sptgroupitemsub.storeEdit');
        Route::get('/deleteSptgroupItemsub', [SptGroupItemSubController::class, 'deleteSptgroupItemSub'])->name('sptgroupitemsub.deleteSptgroupItemSub');

        Route::get('/sptitems', [ItemsController::class, 'listSptItems'])->name('sptitem.listSptItems');
        Route::get('/showFormsptitem', [ItemsController::class, 'showForm'])->name('sptitem.showForm');
        Route::post('/storeSptItem', [ItemsController::class, 'storeSptItem'])->name('sptitem.storeSptItem');
        Route::get('/showFormEditsptitem', [ItemsController::class, 'showFormEdit'])->name('sptitem.showFormEdit');
        Route::post('/storeEditsptitem', [ItemsController::class, 'storeEdit'])->name('sptitem.storeEdit');
        Route::get('/deleteSptItem', [ItemsController::class, 'deleteSptItem'])->name('sptitem.deleteSptItem');

        Route::get('/itemflow', [ItemFlowController::class, 'listItemFlow'])->name('itemflow.listItemFlow');
        Route::get('/showFormitemflow', [ItemFlowController::class, 'showForm'])->name('itemflow.showForm');
        Route::post('/storeFlowItem', [ItemFlowController::class, 'storeFlowItem'])->name('itemflow.storeFlowItem');
        Route::get('/showFormEditFlowItem', [ItemFlowController::class, 'showFormEdit'])->name('itemflow.showFormEdit');
        Route::post('/storeEditFlowItem', [ItemFlowController::class, 'storeEditFlowItem'])->name('itemflow.storeEditFlowItem');
        Route::get('/deleteItemFlow', [ItemFlowController::class, 'deleteItemFlow'])->name('itemflow.deleteItemFlow');

        Route::get('/listJabatan', [JabatanController::class, 'listJabatan'])->name('jabatan.listJabatan');
        Route::post('/replacemenu/{id}', [JabatanController::class, 'replacemenu']);

        Route::get('/listusermenu', [UserMenuController::class, 'listUserMenu'])->name('user.listusermenu');
        Route::post('/replaceusermenu/{id}', [UserMenuController::class, 'replaceusermenu']);

    });

    Route::group(['prefix' => 'report'], function () {
        Route::get('/formreport', [ReportController::class, 'index'])->name('form.report');
        Route::get('/showreport', [ReportController::class, 'showreport'])->name('report.showreport');
    });

});

// Route::group(['middleware' => 'auth'], function(){
//     Route::get('/', function () {
//         return view('welcome');
//     });
// });

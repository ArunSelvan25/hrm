<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagementController;
use App\Http\Controllers\Admin\{Auth\AdminAuthController, RolesAndPermissionController, AdminHouseOwnerManagement};
use App\Http\Controllers\HouseOwner\HouseOwnerAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::controller(FileManagementController::class)->prefix('file')->group(function () {
    Route::post('/upload','simpleUploadFile')->name('file.upload');
    Route::post('/build/disk','onTimeDisks')->name('file.build');
});

Route::prefix('admin')->middleware('adminAuth')->name('admin.')->group(function () {
    Route::controller(AdminAuthController::class)->group(function () {
        Route::get('/login', 'getLogin')->name('get-login')->withoutMiddleware('adminAuth');
        Route::post('/login', 'postLogin')->name('post-login')->withoutMiddleware('adminAuth');
        Route::get('/dashboard', 'getdashboard')->name('get-dashboard');
        Route::get('/logout', 'adminLogout')->name('logout');
//    Route::get('/get-notification','getNotification')->name('get-notification');
        Route::get('/notification/delete', 'deleteNotification')->name('delete');
        Route::get('/notification/mark-as-read/{id}', 'markAsReadNotification')->name('mark-as-read');
    });
    Route::controller(RolesAndPermissionController::class)->group(function () {
        Route::get('/roles-permission', 'viewRolePermission')->name('role-permission');
        Route::post('/create-role', 'createRole')->name('create-role');
    });
    Route::controller(AdminHouseOwnerManagement::class)->group(function () {
        Route::get('/house-owner', 'getHouseOwner')->name('house-owner');
        Route::get('/house-owner/list', 'houseOwnerList')->name('house-owner.list');
        Route::post('/house-owner/edit', 'editHouseOwner')->name('house-owner.edit');
        Route::post('/house-owner/delete', 'deleteHouseOwner')->name('house-owner.delete');
    });
});

Route::controller(HouseOwnerAuthController::class)->prefix('house-owner')->name('house-owner.')->group(function () {
    Route::get('/register','getRegister')->name('get-register');
    Route::post('/register','postRegister')->name('post-register');

});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagementController;
use App\Http\Controllers\Admin\{Auth\AdminAuthController, RolesAndPermissionController, AdminHouseOwnerManagement,
    AdminPropertyManagementController};
use App\Http\Controllers\HouseOwner\HouseOwnerAuthController;
use App\Http\Controllers\{TenantManagementController, UserManagementController, Tenant\Auth\TenantAuthController, User\Auth\UserAuthController};

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

Route::prefix('admin')->middleware('auth:admin,house-owner,tenant,web')->name('admin.')->group(function () {
    Route::controller(AdminAuthController::class)->group(function () {
        Route::get('/login', 'getLogin')->name('get-login');
        Route::post('/login', 'postLogin')->name('post-login');
        Route::get('/dashboard', 'getdashboard')->name('get-dashboard');
        Route::get('/logout', 'logout')->name('logout');
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
//            ->middleware('permission:owner-list');
        Route::get('/house-owner/list', 'houseOwnerList')->name('house-owner.list')
            ->middleware('permission:owner-list');
        Route::post('/house-owner/edit', 'editHouseOwner')->name('house-owner.edit')
            ->middleware('permission:owner-edit');
        Route::post('/house-owner/delete', 'deleteHouseOwner')->name('house-owner.delete')
            ->middleware('permission:owner-delete');
    });

    Route::controller(AdminPropertyManagementController::class)->group(function () {
        Route::get('/property', 'getProperty')->name('property');
        Route::get('/property/list', 'propertyList')->name('property.list');
        Route::post('/property/add', 'addProperty')->name('property.add');
        Route::post('/property/edit', 'editProperty')->name('property.edit');
        Route::post('/property/delete', 'deleteProperty')->name('property.delete');
    });

    Route::controller(TenantManagementController::class)->group(function () {
        Route::get('/tenant', 'getTenant')->name('tenant');
        Route::get('/tenant/list', 'tenantList')->name('tenant.list');
        Route::post('/tenant/add', 'addTenant')->name('tenant.add');
        Route::post('/tenant/edit', 'editTenant')->name('tenant.edit');
        Route::post('/tenant/delete', 'deleteTenant')->name('tenant.delete');
    });

    Route::controller(UserManagementController::class)->group(function () {
        Route::get('/user', 'getUser')->name('user');
        Route::get('/user/list', 'userList')->name('user.list');
        Route::post('/user/add', 'addUser')->name('user.add');
        Route::post('/user/edit', 'editUser')->name('user.edit');
        Route::post('/user/delete', 'deleteUser')->name('user.delete');
    });
});

Route::controller(HouseOwnerAuthController::class)->prefix('house-owner')->name('house-owner.')->group(function () {
    Route::get('/register','getRegister')->name('get-register');
    Route::post('/register','postRegister')->name('post-register');

    Route::get('/login','getLogin')->name('get-login');
    Route::post('/login','postLogin')->name('post-login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(TenantAuthController::class)->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/login','getLogin')->name('get-login');
    Route::post('/login','postLogin')->name('post-login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(UserAuthController::class)->prefix('user')->name('web.')->group(function () {
    Route::get('/login','getLogin')->name('get-login');
    Route::post('/login','postLogin')->name('post-login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RolesAndPermissionController::class)
    ->prefix('role-permission')
    ->name('role-permission.')
    ->group(function () {
    Route::get('/list', 'listRolePermission')->name('list');
    Route::post('/sync-permission', 'syncPermissionWithRole')->name('sync-permission');
});

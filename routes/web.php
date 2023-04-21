<?php

use App\Http\Controllers\{PropertyManagementController,
    HouseOwnerManagementController,
    RolesAndPermissionController,
    Tenant\Auth\TenantAuthController,
    TenantManagementController,
    User\Auth\UserAuthController,
    UserManagementController};
use App\Http\Controllers\Admin\{Auth\AdminAuthController};
use App\Http\Controllers\HouseOwner\HouseOwnerAuthController;
use Illuminate\Support\Facades\Route;

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

// Admin login
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
    Route::get('/login', [AdminAuthController::class,'getLogin'])->name('get-login');
    Route::post('/login', [AdminAuthController::class,'postLogin'])->name('post-login');
});

// Roles and Permission
Route::controller(RolesAndPermissionController::class)
    ->middleware('auth:admin')
    ->prefix('role-permission')
    ->name('role-permission.')
    ->group(function () {
        Route::get('/', 'viewRolePermission')->name('view');
        Route::get('/list', 'listRolePermission')->name('list');
        Route::post('/sync-permission', 'syncPermissionWithRole')->name('sync-permission');
});

Route::get('/dashboard', [AdminAuthController::class,'getdashboard'])->name('get-dashboard');


Route::controller(HouseOwnerManagementController::class)
    ->middleware('auth:admin,house-owner,tenant,web')
    ->group(function () {
        // House owner management
        Route::controller(HouseOwnerManagementController::class)
            ->prefix('house-owner')
            ->name('house-owner.')
            ->group(function () {
                Route::get('/', 'getHouseOwner')->name('house-owner')
                    ->middleware('permission:owner-list');
                Route::get('/list', 'houseOwnerList')->name('list')
                    ->middleware('permission:owner-list');
                Route::post('/edit', 'editHouseOwner')->name('edit')
                    ->middleware('permission:owner-edit');
                Route::post('/delete', 'deleteHouseOwner')->name('delete')
                    ->middleware('permission:owner-delete');
            });

        // Property management
        Route::controller(PropertyManagementController::class)
            ->prefix('property')
            ->name('property.')
            ->group(function () {
                Route::get('/', 'getProperty')->name('property')
                    ->middleware('permission:property-list');
                Route::get('/list', 'propertyList')->name('list')
                    ->middleware('permission:property-list');
                Route::post('/add', 'addProperty')->name('add')
                    ->middleware('permission:property-create');
                Route::post('/edit', 'editProperty')->name('edit')
                    ->middleware('permission:property-edit');
                Route::post('/delete', 'deleteProperty')->name('delete')
                    ->middleware('permission:property-delete');
            });
    });

Route::prefix('admin')->middleware('auth:admin,house-owner,tenant,web')->name('admin.')->group(function () {
    Route::controller(AdminAuthController::class)->group(function () {

//        Route::get('/dashboard', 'getdashboard')->name('get-dashboard');
        Route::get('/logout', 'logout')->name('logout');
//    Route::get('/get-notification','getNotification')->name('get-notification');
        Route::get('/notification/delete', 'deleteNotification')->name('delete');
        Route::get('/notification/mark-as-read/{id}', 'markAsReadNotification')->name('mark-as-read');
    });

    Route::controller(PropertyManagementController::class)->group(function () {
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



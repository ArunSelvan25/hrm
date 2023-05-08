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
Route::prefix('{locale}/admin')
    ->name('admin.')
    ->group(function () {
    Route::get('/login', [AdminAuthController::class,'getLogin'])->name('get-login');
    Route::post('/login', [AdminAuthController::class,'postLogin'])->name('post-login');
});

// Roles and Permission
Route::controller(RolesAndPermissionController::class)
    ->middleware('auth:admin','set_locale')
    ->prefix('{locale}/role-permission')
    ->name('role-permission.')
    ->where(['locale' => '[a-zA-Z]{2}']) // <-- Add a regex to validate the locale
    ->group(function () {
        Route::get('/', 'viewRolePermission')->name('view');
        Route::get('/list', 'listRolePermission')->name('list');
        Route::post('/sync-permission', 'syncPermissionWithRole')->name('sync-permission');
});

Route::get('{locale}/dashboard', [AdminAuthController::class,'getdashboard'])->name('get-dashboard')->middleware('set_locale');


Route::controller(HouseOwnerManagementController::class)
    ->middleware('auth:admin,house-owner,tenant,web,','set_locale')
    ->prefix('{locale}') // <-- Add the locale segment to the URL
    ->where(['locale' => '[a-zA-Z]{2}']) // <-- Add a regex to validate the locale
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

        // Tenant management
        Route::controller(TenantManagementController::class)
        ->prefix('tenant')
        ->name('tenant.')
        ->group(function () {
            Route::get('/', 'getTenant')->name('tenant')
                ->middleware('permission:tenant-list');
            Route::get('/list', 'tenantList')->name('list')
                ->middleware('permission:tenant-list');
            Route::post('/add', 'addTenant')->name('add')
                ->middleware('permission:tenant-create');
            Route::post('/edit', 'editTenant')->name('edit')
                ->middleware('permission:tenant-edit');
            Route::post('/delete', 'deleteTenant')->name('delete')
                ->middleware('permission:tenant-delete');
        });

        // User management
        Route::controller(UserManagementController::class)
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/', 'getUser')->name('user')
                ->middleware('permission:user-list');
            Route::get('/list', 'userList')->name('list')
                ->middleware('permission:user-list');
            Route::post('/add', 'addUser')->name('add')
                ->middleware('permission:user-create');
            Route::post('/edit', 'editUser')->name('edit')
                ->middleware('permission:user-edit');
            Route::post('/delete', 'deleteUser')->name('delete')
                ->middleware('permission:user-delete');
        });
    });

Route::prefix('{locale}/admin')->middleware('auth:admin,house-owner,tenant,web')->name('admin.')->group(function () {
    Route::controller(AdminAuthController::class)->group(function () {

        Route::get('/logout', 'logout')->name('logout');
//    Route::get('/get-notification','getNotification')->name('get-notification');
        Route::get('/notification/delete', 'deleteNotification')->name('delete');
        Route::get('/notification/mark-as-read/{id}', 'markAsReadNotification')->name('mark-as-read');
    });
});

Route::controller(HouseOwnerAuthController::class)->prefix('{locale}/house-owner')->name('house-owner.')->group(function () {
    Route::get('/register','getRegister')->name('get-register');
    Route::post('/register','postRegister')->name('post-register');

    Route::get('/login','getLogin')->name('get-login');
    Route::post('/login','postLogin')->name('post-login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(TenantAuthController::class)->prefix('{locale}/tenant')->name('tenant.')->group(function () {
    Route::get('/login','getLogin')->name('get-login');
    Route::post('/login','postLogin')->name('post-login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(UserAuthController::class)->prefix('{locale}/user')->name('web.')->group(function () {
    Route::get('/login','getLogin')->name('get-login');
    Route::post('/login','postLogin')->name('post-login');
    Route::get('/logout', 'logout')->name('logout');
});



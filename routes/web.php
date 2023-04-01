<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagementController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
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

Route::controller(AdminAuthController::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login','getLogin')->name('get-login');
    Route::post('/login','postLogin')->name('post-login');
    Route::get('/dashboard','getdashboard')->name('get-dashboard');
    Route::get('/logout','adminLogout')->name('logout');
//    Route::get('/get-notification','getNotification')->name('get-notification');
    Route::get('/notification/delete','deleteNotification')->name('delete');
    Route::get('/notification/mark-as-read/{id}','markAsReadNotification')->name('mark-as-read');

});

Route::controller(HouseOwnerAuthController::class)->prefix('house-owner')->name('house-owner.')->group(function () {
    Route::get('/register','getRegister')->name('get-register');
    Route::post('/register','postRegister')->name('post-register');

});

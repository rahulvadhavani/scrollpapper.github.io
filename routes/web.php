<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'admin'],function(){

Route::get('index',[App\Http\Controllers\AdminController::class ,'getIndex']);

Route::get('login/{sec_token?}',[App\Http\Controllers\AdminController::class ,'getLogin']);
Route::get('logout',[App\Http\Controllers\AdminController::class ,'getLogout']);
Route::get('dashboard',[App\Http\Controllers\AdminController::class ,'getDashboard']);
Route::get('settings',[App\Http\Controllers\AdminController::class ,'getSettings']);
Route::get('wallpappers',[App\Http\Controllers\AdminController::class ,'getwallpappers']);
Route::get('category',[App\Http\Controllers\AdminController::class ,'getCategory']);

// POST METHODS

Route::post('login',[App\Http\Controllers\AdminController::class ,'postLogin']);
Route::post('save-settings',[App\Http\Controllers\AdminController::class ,'postSaveSettings']);
Route::post('users-filter',[App\Http\Controllers\AdminController::class ,'postUsersFilter']);
Route::post('add-property',[App\Http\Controllers\AdminController::class ,'postAddProperty']);
Route::post('property-filter',[App\Http\Controllers\AdminController::class ,'postPropertyFilter']);
Route::post('upload-image',[App\Http\Controllers\AdminController::class ,'postUploadImages']);
Route::post('wallpapper-filter',[App\Http\Controllers\AdminController::class ,'postWallpapperFilter']);
Route::post('delete-wallpapper',[App\Http\Controllers\AdminController::class ,'postDeletewallpapper']);
Route::post('update-wallpapper',[App\Http\Controllers\AdminController::class ,'postUpdateWallpapper']);
Route::post('category-filter',[App\Http\Controllers\AdminController::class ,'postCategoryFilter']);
Route::post('add-category',[App\Http\Controllers\AdminController::class ,'postAddCategory']);
Route::post('delete-category',[App\Http\Controllers\AdminController::class ,'postDeleteCategory']);
Route::post('update-category',[App\Http\Controllers\AdminController::class ,'postUpdateCategory']);


});
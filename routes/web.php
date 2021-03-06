<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDetailsController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\SellDetailsController;

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

Route::prefix('/api')->group(function () {
    //rutas especificass
    Route::post('/user/login',[UserController::class,'login']);
    Route::post('/user/getidentity',[UserController::class,'getIdentity']);
    Route::post('/user/upload',[UserController::class,'uploadImage']);
    Route::get('/user/getimage/{filename}',[UserController::class,'getImage']);
    Route::post('/product/upload',[ProductController::class,'uploadImage']);
    Route::get('/product/getimage/{filename}',[ProductController::class,'getImage']);
    //Rutas automaticas RESTful
    Route::resource('/user',UserController::class,['except'=>['create','edit']]);
    Route::resource('/client',ClientController::class,['except'=>['create','edit']]);
    Route::resource('/product',ProductController::class,['except'=>['create','edit']]);
    Route::resource('/provider',ProviderController::class,['except'=>['create','edit']]);
    Route::resource('/employee',EmployeeController::class,['except'=>['create','edit']]);
    Route::resource('/purchase',PurchaseController::class,['except'=>['create','edit']]);
    Route::resource('/purchasedetails',PurchaseDetailsController::class,['except'=>['create','edit']]);
    Route::resource('/sell',SellController::class,['except'=>['create','edit']]);
    Route::resource('/selldetails',SellDetailsController::class,['except'=>['create','edit']]);
});
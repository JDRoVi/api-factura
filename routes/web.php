<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;

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
    //rutas especificas

    //Rutas automaticas RESTful
    Route::resource('/user',UserController::class,['except'=>['create','edit']]);
    Route::resource('/client',ClientController::class,['except'=>['create','edit']]);
});

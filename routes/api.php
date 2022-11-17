<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;

use App\Http\Controllers\API\UsersController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(RegisterController::class)->group(function(){

    Route::post('register', 'register');

    Route::post('login', 'login');

});

Route::controller(UsersController::class)->middleware('auth:sanctum')->group( function () {
    
    Route::put('add-wallet-balance', 'addWalletBalance');
    Route::post('buy-cookie', 'buyCoockie');
});
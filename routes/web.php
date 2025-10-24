<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'auth','as' => 'auth.'],function(){
    Route::get('/login',[AuthController::class,'viewLogin'])->name('login');
    Route::post('/login',[AuthController::class,'doLogin'])->name('dologin');
});

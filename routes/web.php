<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'auth','as' => 'auth.'],function(){
    Route::get('/login',Login::class)->name('login');
});

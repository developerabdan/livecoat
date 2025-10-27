<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Settings\Permission\Permission;
use App\Livewire\Settings\PermissionGroup\PermissionGroup;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'auth','as' => 'auth.'],function(){
    Route::get('/login',Login::class)->name('login');
});

Route::group(['prefix' => 'app','as' => 'app.'],function(){
    Route::get('/dashboard',Dashboard::class)->name('dashboard');

    Route::group(['prefix' => 'settings','as' => 'settings.'],function(){
        Route::get('/permission-group',PermissionGroup::class)->name('permission-group');
        Route::get('/permission',Permission::class)->name('permission');
    });
});
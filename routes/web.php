<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Settings\Permission\Permission;
use App\Livewire\Settings\PermissionGroup\PermissionGroup;
use App\Livewire\Settings\Role\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'auth','as' => 'auth.','middleware' => 'guest'],function(){
    Route::get('/login',Login::class)->name('login');
});

Route::group(['prefix' => 'app','as' => 'app.', 'middleware' => 'auth'],function(){
    Route::get('/dashboard',Dashboard::class)->name('dashboard');

    Route::group(['prefix' => 'settings','as' => 'settings.'],function(){
        Route::get('/permission-group',PermissionGroup::class)->name('permission-group');
        Route::get('/permission',Permission::class)->name('permission');
        Route::get('/role',Role::class)->name('role');
    });
    Route::get('/logout',function(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    })->name('logout');
});
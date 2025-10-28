<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Profile\Profile;
use App\Livewire\Settings\Permission\Permission;
use App\Livewire\Settings\PermissionGroup\PermissionGroup;
use App\Livewire\Settings\Role\Role;
use App\Livewire\UserManagement\UserManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'auth','as' => 'auth.','middleware' => 'guest'],function(){
    Route::get('/login',Login::class)->name('login');
});

Route::group(['prefix' => 'app','as' => 'app.', 'middleware' => 'auth'],function(){
    Route::get('/dashboard',Dashboard::class)->name('dashboard');
    Route::get('/user',UserManagement::class)
            ->can('View Users')
            ->name('user');
    Route::get('/profile',Profile::class)->name('profile');

    Route::group(['prefix' => 'settings','as' => 'settings.'],function(){
        Route::get('/permission-group',PermissionGroup::class)
            ->middleware('can:View Permission Groups')
            ->name('permission-group');
        Route::get('/permission',Permission::class)
            ->middleware('can:View Permissions')
            ->name('permission');
        Route::get('/role',Role::class)
            ->middleware('can:View Roles')
            ->name('role');
    });
    Route::get('/logout',function(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    })->name('logout');
});
<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function viewLogin()
    {
        return view('auth.login');
    }
    public function AuthController(LoginRequest $request)
    {

    }
}

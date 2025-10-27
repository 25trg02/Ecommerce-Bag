<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('admin.dashboard'); // @extends('layouts.admin')
        } elseif (Auth::check() && Auth::user()->role === 'user') {
            return view('user.dashboard'); // @extends('layouts.user')
        } else {
            return view('welcome'); // @extends('layouts.app')
        }
    }
}

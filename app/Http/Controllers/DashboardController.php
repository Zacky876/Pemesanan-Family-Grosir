<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //mengarahkan sesuai role
    public function admin()
    {
        return view('admin.dashboard');
    }

    public function kasir()
    {
        return view('kasir.dashboard');
    }

    public function user()
    {
        return view('user.dashboard');
    }
}

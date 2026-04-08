<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MitraMobilController extends Controller
{
    public function dashboard()
    {
        return view('mitra.dashboard');
    }
}

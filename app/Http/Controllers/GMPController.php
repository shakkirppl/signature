<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GMPController extends Controller
{
    public function create()
    {
        
        return view('gmp-dailychecklist.create');
    }
}

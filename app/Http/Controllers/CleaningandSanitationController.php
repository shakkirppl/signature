<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CleaningandSanitationController extends Controller
{
    public function create()
    {
        
        return view('cleaning-sanitation.create');
    }
}

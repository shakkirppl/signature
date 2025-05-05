<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CorrectiveActionController extends Controller
{
    public function create()
    {
        
        return view('corrective-action.create');
    }
}

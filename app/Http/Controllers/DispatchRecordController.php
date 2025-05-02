<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DispatchRecordController extends Controller
{
    public function create()
    {
        
        return view('dispatch-record.create');
    }
}

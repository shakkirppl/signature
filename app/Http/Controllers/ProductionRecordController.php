<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductionRecordController extends Controller
{
    public function create()
    {
        
        return view('production-record.create');
    }
}

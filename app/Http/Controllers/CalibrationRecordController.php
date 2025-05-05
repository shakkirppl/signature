<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalibrationRecordController extends Controller
{
    public function create()
    {
        
        return view('calibration-record.create');
    }
}

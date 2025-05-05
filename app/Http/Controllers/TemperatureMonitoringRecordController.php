<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemperatureMonitoringRecordController extends Controller
{
    public function create()
    {
        
        return view('temperature-monitoring.create');
    }
}

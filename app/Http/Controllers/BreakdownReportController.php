<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BreakdownReportController extends Controller
{
    public function create()
    {
        return view('breakdown-report.create');
    }
}

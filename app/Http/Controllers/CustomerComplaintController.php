<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerComplaintController extends Controller
{
    public function create()
    {
        
        return view('customer-complaint.create');
    }
}

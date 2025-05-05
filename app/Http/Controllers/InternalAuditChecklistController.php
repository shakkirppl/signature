<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InternalAuditChecklistController extends Controller
{
    public function create()
    {
        
        return view('internal-auditchecklist.create');
    }
}

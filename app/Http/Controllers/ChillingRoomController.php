<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChillingRoomController extends Controller
{
    public function create()
    {
        return view('chilling-room.create');
    }
}

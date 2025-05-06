<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChillingRoom;

class ChillingRoomController extends Controller
{
    public function create()
    {
        return view('chilling-room.create');
    }

public function store(Request $request)
{
  
    $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'Product_id' => 'nullable|string', 
        'chiller_temp_humidity' => 'required|string',
    ]);

   
    ChillingRoom::create([
        'date' => $request->date,
        'time' => $request->time,
        'initial_carcass_temp' => $request->input('initial_carcass_temp'),
        'exit_temp_carcass' => $request->input('exit_temp_carcass'),
        'chiller_temp_humidity' => $request->chiller_temp_humidity,
        'user_id' => Auth::id(),
        'store_id' => 1,
    ]);

    return redirect()->route('chilling-room.index')->with('success', 'Chilling room log saved successfully.');
}

}

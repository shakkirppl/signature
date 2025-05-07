<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChillingRoom;
use Illuminate\Support\Facades\Auth;


class ChillingRoomController extends Controller
{
    public function index()
{
    $records = ChillingRoom::orderBy('date', 'desc')->get();
    return view('chilling-room.index', compact('records'));
}

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
public function edit($id)
{
    $record = ChillingRoom::findOrFail($id);
    return view('chilling-room.edit', compact('record'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'date' => 'required|date',
        'initial_carcass_temp' => 'nullable|string',
        'exit_temp_carcass' => 'required|string',
        'time' => 'required|string',
        'chiller_temp_humidity' => 'required|string',
    ]);

    $record = ChillingRoom::findOrFail($id);
    $record->update([
        'date' => $request->date,
        'initial_carcass_temp' => $request->initial_carcass_temp,
        'exit_temp_carcass' => $request->exit_temp_carcass,
        'time' => $request->time,
        'chiller_temp_humidity' => $request->chiller_temp_humidity,
    ]);

    return redirect()->route('chilling-room.index')->with('success', 'Record updated successfully.');
}


public function destroy($id)
{
    $record = ChillingRoom::findOrFail($id);
    $record->delete();

    return redirect()->route('chilling-room.index')->with('success', 'Record deleted successfully.');
}


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GmpChecklist;
use Illuminate\Support\Facades\Auth;

class GMPController extends Controller
{
    public function index()
{
    $records = GmpChecklist::latest()->get();
    return view('gmp-dailychecklist.index', compact('records'));
}
    public function create()
    {
        
        return view('gmp-dailychecklist.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'facility_cleanliness' => 'required|string',
        'pest_control' => 'required|string',
        'personal_hygiene' => 'nullable|string',
        'equipment_sanitation' => 'required|string',
    ]);

    

    GmpChecklist::create([
        'date' => $request->date,
        'facility_cleanliness' => $request->facility_cleanliness,
        'pest_control' => $request->pest_control,
        'personal_hygiene' => $request->personal_hygiene,
        'equipment_sanitation' => $request->equipment_sanitation,
          'user_id' => Auth::id(),
        'store_id' => 1,
        
    ]);

    return redirect()->route('gmp.index')->with('success', 'GMP Checklist submitted successfully.');
}
public function edit($id)
{
    $record = GmpChecklist::findOrFail($id);
    return view('gmp-dailychecklist.edit', compact('record'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'date' => 'required|date',
        'facility_cleanliness' => 'required|string',
        'pest_control' => 'required|string',
        'personal_hygiene' => 'nullable|string',
        'equipment_sanitation' => 'required|string',
    ]);

    $record = GmpChecklist::findOrFail($id);
    $record->update([
        'date' => $request->date,
        'facility_cleanliness' => $request->facility_cleanliness,
        'pest_control' => $request->pest_control,
        'personal_hygiene' => $request->personal_hygiene,
        'equipment_sanitation' => $request->equipment_sanitation,
    ]);

    return redirect()->route('gmp.index')->with('success', 'GMP Checklist updated successfully.');
}


public function destroy($id)
{
    $record = GmpChecklist::findOrFail($id);
    $record->delete();
 return redirect()->route('gmp.index')->with('success', 'Record deleted successfully.');
}

}

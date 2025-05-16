<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\CorrectiveAction;

class CorrectiveActionController extends Controller
{
    public function index()
{
    $records = CorrectiveAction::with('user')->orderBy('id', 'desc')->get();
    return view('corrective-action.index', compact('records'));
}
    public function create()
    {
        
        return view('corrective-action.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'non_conformity' => 'required|string',
        'action_taken' => 'required|string',
        'responsible_person' => 'required|string',
        'department' => 'nullable|string',
        'root_cause' => 'required|string',
        'date_of_completion' => 'required|date',
        'verified_by' => 'required|string',
        'signature' => 'required|string', // base64 signature
    ]);

    $fileName = null;

    if ($request->signature) {
        $base64Image = $request->signature;

        // Remove the base64 prefix
        $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
        $base64Image = str_replace(' ', '+', $base64Image);
        $imageData = base64_decode($base64Image);

        // Generate unique filename
        $fileName = 'corrective_signature_' . time() . '_' . Str::random(10) . '.png';

        // Store the file under public disk in `signatures/`
        Storage::disk('public')->put('signatures/' . $fileName, $imageData);
    }

    CorrectiveAction::create([
        'date' => $request->date,
        'non_conformity' => $request->non_conformity,
        'action_taken' => $request->action_taken,
        'responsible_person' => $request->responsible_person,
        'department' => $request->department,
        'root_cause' => $request->root_cause,
        'date_of_completion' => $request->date_of_completion,
        'verified_by' => $request->verified_by,
        'signature' => $fileName,
        'user_id' => Auth::id(), 
         'store_id' => 1,

    ]);

    return redirect()->route('corrective-action.index')->with('success', 'Corrective Action Report submitted successfully!');
}

public function destroy($id)
{
    $record = CorrectiveAction::findOrFail($id);
    $record->delete();
 return redirect()->route('corrective-action.index')->with('success', 'Record deleted successfully.');
}
}

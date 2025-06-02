<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CleaningSanitationRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CleaningandSanitationController extends Controller
{
public function index()
{
    $records = CleaningSanitationRecord::with('user')->latest()->get();
    return view('cleaning-sanitation.index', compact('records'));
}

    public function create()
    {
        
        return view('cleaning-sanitation.create');
    }


    public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'cleaning_method' => 'required|string',
        'cleaning_agent' => 'required|string',
        'area_cleaned' => 'nullable|string',
        'cleaner_name' => 'required|string',
        'supervisor_check' => 'required|in:Yes,No',
        'verification_signature' => 'required',
    ]);
$fileName = null;

if ($request->verification_signature) {
    $base64Image = $request->verification_signature;

    // Extract base64 content
    $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
    $base64Image = str_replace(' ', '+', $base64Image);
    $imageData = base64_decode($base64Image);

    // Create unique file name
    $fileName = 'signature_' . time() . '_' . Str::random(10) . '.png';

    // Save to public storage disk under 'signatures' directory
    Storage::disk('public')->put('signatures/' . $fileName, $imageData);
}
    CleaningSanitationRecord::create([
        'date' => $request->date,
        'cleaning_method' => $request->cleaning_method,
        'cleaning_agent' => $request->cleaning_agent,
        'area_cleaned' => $request->area_cleaned,
        'cleaner_name' => $request->cleaner_name,
        'supervisor_check' => $request->supervisor_check,
        'verification_signature' => $fileName,
          'store_id' => 1,
        'user_id' => auth()->id(),
        'comments' => $request->comments,
        
    ]);

    return redirect()->route('cleaning-sanitation.index')->with('success', 'Record saved successfully.');
}

public function edit($id)
{
    $record = CleaningSanitationRecord::findOrFail($id);
    return view('cleaning-sanitation.edit', compact('record'));
}

public function update(Request $request, $id)
{
    $record = CleaningSanitationRecord::findOrFail($id);

    $request->validate([
        'date' => 'required|date',
        'cleaning_method' => 'required|string',
        'cleaning_agent' => 'required|string',
        'area_cleaned' => 'nullable|string',
        'cleaner_name' => 'required|string',
        'supervisor_check' => 'required|in:Yes,No',
        'verification_signature' => 'nullable',
    ]);

    $fileName = $record->verification_signature; // keep old signature if not changed

    if ($request->verification_signature) {
        $base64Image = $request->verification_signature;

        if (!Str::startsWith($base64Image, 'data:image')) {
            // ignore if signature was not re-drawn
        } else {
            $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
            $base64Image = str_replace(' ', '+', $base64Image);
            $imageData = base64_decode($base64Image);

            $fileName = 'signature_' . time() . '_' . Str::random(10) . '.png';
            Storage::disk('public')->put('signatures/' . $fileName, $imageData);
        }
    }

    $record->update([
        'date' => $request->date,
        'cleaning_method' => $request->cleaning_method,
        'cleaning_agent' => $request->cleaning_agent,
        'area_cleaned' => $request->area_cleaned,
        'cleaner_name' => $request->cleaner_name,
        'supervisor_check' => $request->supervisor_check,
        'verification_signature' => $fileName,
        'comments' => $request->comments,
    ]);

    return redirect()->route('cleaning-sanitation.index')->with('success', 'Record updated successfully.');
}

public function destroy($id)
{
    $record = CleaningSanitationRecord::findOrFail($id);
    $record->delete();
 return redirect()->route('cleaning-sanitation.index')->with('success', 'Record deleted successfully.');
}
}

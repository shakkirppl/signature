<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalibrationRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CalibrationRecordController extends Controller
{

    public function index()
{
    $records = CalibrationRecord::with('user')->latest()->get();
    return view('calibration-record.index', compact('records'));
}

    public function create()
    {
        
        return view('calibration-record.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'equipment_name' => 'required|string|max:255',
        'standard_used' => 'required|string|max:255',
        'calibration_result' => 'required|string',
        'next_calibration_due' => 'required|date',
        'technician_name' => 'required|string|max:255',
        'signature' => 'required|string',
    ]);
$fileName = null;

if ($request->signature) {
    $base64Image = $request->signature;

    // Extract base64 content
    $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
    $base64Image = str_replace(' ', '+', $base64Image);
    $imageData = base64_decode($base64Image);

    // Create unique file name
    $fileName = 'signature_' . time() . '_' . Str::random(10) . '.png';

    // Save to public storage disk under 'signatures' directory
    Storage::disk('public')->put('signatures/' . $fileName, $imageData);
}
    CalibrationRecord::create([
        'date' => $request->date,
        'equipment_name' => $request->equipment_name,
        'standard_used' => $request->standard_used,
        'calibration_result' => $request->calibration_result,
        'next_calibration_due' => $request->next_calibration_due,
        'technician_name' => $request->technician_name,
        'signature' => $fileName,
        'user_id' => auth()->id(),
         'store_id' => 1,
    ]);

    return redirect()->route('calibration-record.index')->with('success', 'Record created successfully.');
}

public function destroy($id)
{
    $record = CalibrationRecord::findOrFail($id);
    $record->delete();
 return redirect()->route('calibration-record.index')->with('success', 'Record deleted successfully.');
}

}

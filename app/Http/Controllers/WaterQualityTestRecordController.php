<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaterQualityTestRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class WaterQualityTestRecordController extends Controller
{
    public function index()
{
    $records = WaterQualityTestRecord::with('user')->latest()->get();
    return view('water-quality.index', compact('records'));
}

    public function create()
{
    return view('water-quality.create');
}

public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'sampling_point' => 'required|string|max:255',
        'test_parameters' => 'required|string',
        'results' => 'required|string',
        'standards_met' => 'required|boolean',
        'lab_technician' => 'required|string|max:255',
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

    WaterQualityTestRecord::create([
        'date' => $request->date,
        'sampling_point' => $request->sampling_point,
        'test_parameters' => $request->test_parameters,
        'results' => $request->results,
        'standards_met' => $request->standards_met,
        'lab_technician' => $request->lab_technician,
        'signature' => $fileName,
        'user_id' => auth()->id(),
        
         'store_id' => 1,
    ]);

    return redirect()->route('water-quality.index')->with('success', 'Water Quality Test Record created successfully.');
}

public function destroy($id)
{
    $record = WaterQualityTestRecord::findOrFail($id);
    $record->delete();
 return redirect()->route('water-quality.index')->with('success', 'Record deleted successfully.');
}

}

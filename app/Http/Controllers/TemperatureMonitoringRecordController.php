<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemperatureMonitoringRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TemperatureMonitoringRecordController extends Controller
{
    public function index()
{
    $records = TemperatureMonitoringRecord::orderBy('id', 'desc')->get();
    return view('temperature-monitoring.index', compact('records'));
}

    public function create()
    {
        
        return view('temperature-monitoring.create');
    }
// 
public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'temp_before' => 'required',
        'temp_after' => 'required',
        'average_carcass' => 'required',
        'inspector_name' => 'required',
        'inspector_signature' => 'required', // base64 validation
    ]);

    $fileName = null;

if ($request->inspector_signature) {
    $base64Image = $request->inspector_signature;

    // Extract base64 content
    $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
    $base64Image = str_replace(' ', '+', $base64Image);
    $imageData = base64_decode($base64Image);

    // Create unique file name
    $fileName = 'signature_' . time() . '_' . Str::random(10) . '.png';

    // Save to public storage disk under 'signatures' directory
    Storage::disk('public')->put('signatures/' . $fileName, $imageData);
}

    TemperatureMonitoringRecord::create([
        'date' => $request->date,
        'time' => $request->time,
        'temp_before' => $request->temp_before,
        'temp_after' => $request->temp_after,
        'slaughter_area' => $request->slaughter_area,
        'average_carcass' => $request->average_carcass,
        'inspector_name' => $request->inspector_name,
        'inspector_signature' => $fileName,
        'user_id' => Auth::id(),
        'store_id' => 1,
    ]);

    return redirect()->route('temperature-monitoring.index')->with('success', 'Record saved successfully!');
}

}

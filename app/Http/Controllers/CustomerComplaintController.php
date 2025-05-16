<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerComplaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CustomerComplaintController extends Controller
{
    public function index()
{
    $records = CustomerComplaint::with('user')->latest()->get();

    return view('customer-complaint.index', compact('records'));
}

    public function create()
    {
        
        return view('customer-complaint.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'date_received' => 'required|date',
        'customer_name' => 'required|string',
        'complaint_details' => 'required|string',
        'investigation_findings' => 'required|string',
        'corrective_action' => 'required|string',
        'responsible_person' => 'required|string',
        'date_closed' => 'required|date',
        'manager_signature' => 'required|string', 
    ]);

   $fileName = null;

if ($request->manager_signature) {
    $base64Image = $request->manager_signature;

    // Extract base64 content
    $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
    $base64Image = str_replace(' ', '+', $base64Image);
    $imageData = base64_decode($base64Image);

    // Create unique file name
    $fileName = 'signature_' . time() . '_' . Str::random(10) . '.png';

    // Save to public storage disk under 'signatures' directory
    Storage::disk('public')->put('signatures/' . $fileName, $imageData);
}

    CustomerComplaint::create([
        'date_received' => $request->date_received,
        'customer_name' => $request->customer_name,
        'complaint_details' => $request->complaint_details,
        'investigation_findings' => $request->investigation_findings,
        'corrective_action' => $request->corrective_action,
        'responsible_person' => $request->responsible_person,
        'date_closed' => $request->date_closed,
        'manager_signature' => $fileName,
        'user_id' => Auth::id(), 
        'store_id' => 1,
    ]);

    return redirect()->route('customer-complaint.index')->with('success', 'Customer complaint submitted successfully!');
}

public function destroy($id)
{
    $record = CustomerComplaint::findOrFail($id);
    $record->delete();
 return redirect()->route('customer-complaint.index')->with('success', 'Record deleted successfully.');
}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    use App\Models\InternalAuditChecklist;
    use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InternalAuditChecklistController extends Controller
{
    public function index()
{
    $records = InternalAuditChecklist::orderBy('audit_date', 'desc')->get();
    return view('internal-auditchecklist.index', compact('records'));
}
    public function create()
    {
        
        return view('internal-auditchecklist.create');
    }

public function store(Request $request)
{
    $request->validate([
        'audit_date' => 'required|date',
        'area_audited' => 'required|string',
        'auditor_name' => 'required|string',
        'follow_up_date' => 'required|date',
        'auditor_signature' => 'required', 
    ]);
      $fileName = null;

if ($request->auditor_signature) {
    $base64Image = $request->auditor_signature;

    // Extract base64 content
    $base64Image = str_replace('data:image/png;base64,', '', $base64Image);
    $base64Image = str_replace(' ', '+', $base64Image);
    $imageData = base64_decode($base64Image);

    // Create unique file name
    $fileName = 'signature_' . time() . '_' . Str::random(10) . '.png';

    // Save to public storage disk under 'signatures' directory
    Storage::disk('public')->put('signatures/' . $fileName, $imageData);
}

    InternalAuditChecklist::create([
        'audit_date' => $request->audit_date,
        'area_audited' => $request->area_audited,
        'auditor_name' => $request->auditor_name,
        'non_conformities_found' => $request->non_conformities_found,
        'corrective_actions_needed' => $request->corrective_actions_needed,
        'follow_up_date' => $request->follow_up_date,
        'auditor_signature' =>$fileName, 
         'user_id' => Auth::id(), 
        'store_id' => 1,
    ]);

    return redirect()->route('internal-auditchecklist.index')->with('success', 'Audit checklist saved successfully!');
}

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\AntemortemMaster;
use App\Models\AntemortemAnimalInspection;
use App\Models\AntemortemGeneralCondition;
use App\Models\AntemortemSampleSubmission;
use App\Models\AntemortemComment;
use Illuminate\Support\Facades\DB;

class AnteMortemReportController extends Controller
{
    public function create()
    {
        return view('antemortem-report.create',[ 'invoice_no' => $this->invoice_no()]);
    }

    public function index()
    {
        $reports = AntemortemMaster::paginate(10); // Pagination
        return view('antemortem-report.index', compact('reports'));
    }
    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('antemortem_no',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
        }


        public function store(Request $request)
        {
            $request->validate([
                'inspection_date' => 'required|date',
                'antemortem_no' => 'required|string',
                'quantity_pass.*' => 'nullable|integer',
                'quantity_held.*' => 'nullable|integer',
                'quantity_condemned.*' => 'nullable|integer',
                'vet_contacted.*' => 'nullable|string',
                'manager_contacted.*' => 'nullable|string',
                'sample_identification_type.*' => 'nullable|string',
                'sample_location.*' => 'nullable|string',
                'hold_tag.*' => 'nullable|string',
                'date_submitted.*' => 'nullable|date',
                'comments.*' => 'nullable|string',
            ]);
        
            try {
                DB::beginTransaction();
        
                InvoiceNumber::updateinvoiceNumber('antemortem_no', 1);
        
                $report = AntemortemMaster::create([
                    'antemortem_no' => $request->antemortem_no,
                    'inspection_date' => $request->inspection_date,
                    'user_id' => Auth::id(),
                    'store_id' => 1,
                ]);
        
                if (!empty($request->animal_type)) {
                    foreach ($request->animal_type as $index => $animal) {
                        AntemortemAnimalInspection::create([
                            'report_id' => $report->id,
                            'animal_type' => $animal,
                            'quantity_pass' => $request->quantity_pass[$index] ?? null,
                            'quantity_held' => $request->quantity_held[$index] ?? null,
                            'quantity_condemned' => $request->quantity_condemned[$index] ?? null,
                            'vet_contacted' => $request->vet_contacted[$index] ?? null,
                            'manager_contacted' => $request->manager_contacted[$index] ?? null,
                        ]);
                    }
                }
        
                if (!empty($request->condition_type)) {
                    foreach ($request->condition_type as $index => $condition) {
                        AntemortemGeneralCondition::create([
                            'report_id' => $report->id,
                            'condition_type' => $condition,
                            'suspect' => $request->suspect[$index] ?? null,
                            'not_suspect' => $request->not_suspect[$index] ?? null,
                        ]);
                    }
                }
        
                if (!empty($request->sample_identification_type)) {
                    foreach ($request->sample_identification_type as $index => $sampleType) {
                        AntemortemSampleSubmission::create([
                            'report_id' => $report->id,
                            'sample_identification_type' => $sampleType,
                            'sample_location' => $request->sample_location[$index] ?? null,
                            'hold_tag' => $request->hold_tag[$index] ?? null,
                            'date_submitted' => $request->date_submitted[$index] ?? null,
                        ]);
                    }
                }
        
                if (!empty($request->comments)) {
                    foreach ($request->comments as $commentText) {
                        if (!empty($commentText)) {
                            AntemortemComment::create([
                                'report_id' => $report->id,
                                'comment_text' => $commentText,
                            ]);
                        }
                    }
                }
        
                DB::commit();
        
                return redirect()->route('antemortem.index')->with('success', 'Antemortem report created successfully.');
        
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Antemortem store error: ' . $e->getMessage());
        
                return redirect()->back()->with('error', 'Failed to save data. Error: ' . $e->getMessage());
            }
        }
        
        public function edit($id)
        {
            $report = AntemortemMaster::with(['animal', 'condition', 'sampleType', 'comment'])->findOrFail($id);
        
            return view('antemortem-report.edit', compact('report'));
        }
        

}

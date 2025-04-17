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
            // return $request->all();
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
                            'quantity_pass' => $request->quantity_pass[$animal] ?? 0,
                            'quantity_held' => $request->quantity_held[$animal] ?? 0,
                            'quantity_condemned' => $request->quantity_condemned[$animal] ?? 0,
                            'vet_contacted' => $request->vet_contacted[$animal] ?? null,
                            'manager_contacted' => $request->manager_contacted[$animal] ?? null,
                        ]);
                    }
                }
        
                $conditions = [
                    'reportable_diseases' => 'Reportable diseases: visual suspicion of BSE, Foot and Mouth, etc.',
                    'health_risk' => 'Other health risk to staff: visual suspicion of ringworm, enraged animal, etc.',
                    'unfit_consumption' => 'Unfit for consumption: visual suspicion for emaciation, multiple abscess, etc.',
                    'antibiotics' => 'Antibiotics: visual evidence of needle marks, down animals, cull animals.',
                    'contamination' => 'Heavy contamination: visual evidence of excessively contaminated animals.',
                    'welfare' => 'Animal welfare: evidence of abuse, improper conditions, etc.',
                    'feeding' => 'Feeding: evidence that animals have not been taken off feed prior to slaughter.',
                ];
                
                foreach ($conditions as $key => $description) {
                    AntemortemGeneralCondition::create([
                        'report_id' => $report->id,
                        'condition_type' => $description,
                        'suspect' => $request->suspect[$key] ?? null,
                        'not_suspect' => $request->not_suspect[$key] ?? null,
                    ]);
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
        
                if (!empty($request->comment_text)) {
                    // Loop through each comment and insert it if not empty
                    foreach ($request->comment_text as $commentText) {
                        if (!empty($commentText)) {
                            AntemortemComment::create([
                                'report_id' =>  $report->id,  
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
            $generalConditions = AntemortemGeneralCondition::where('report_id', $report->id)->get();

        
            return view('antemortem-report.edit', compact('report','generalConditions'));
        }
        
        public function update(Request $request, $id)
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
        
                $report = AntemortemMaster::findOrFail($id);
                $report->update([
                    'antemortem_no' => $request->antemortem_no,
                    'inspection_date' => $request->inspection_date,
                    'user_id' => Auth::id(),
                    'store_id' => 1,
                ]);
        
                // Update Animal Inspection
                AntemortemAnimalInspection::where('report_id', $report->id)->delete();
                if (!empty($request->animal_type)) {
                    foreach ($request->animal_type as $index => $animal) {
                        AntemortemAnimalInspection::create([
                            'report_id' => $report->id,
                            'animal_type' => $animal,
                            'quantity_pass' => $request->quantity_pass[$index] ?? 0,
                            'quantity_held' => $request->quantity_held[$index] ?? 0,
                            'quantity_condemned' => $request->quantity_condemned[$index] ?? 0,
                            'vet_contacted' => $request->vet_contacted[$index] ?? null,
                            'manager_contacted' => $request->manager_contacted[$index] ?? null,
                        ]);
                    }
                }
        
                if (!empty($request->conditions)) {
                    foreach ($request->conditions as $key => $condition) {
                        $generalCondition = AntemortemGeneralCondition::where('report_id', $report->id)
                            ->where('condition_type', $condition['condition_type'])
                            ->first();
            
                        if ($generalCondition) {
                            $generalCondition->update([
                                'suspect' => $condition['suspect'],
                                'not_suspect' => $condition['not_suspect'],
                            ]);
                        }
                    }
                }
        
                // Update Sample Submissions
                AntemortemSampleSubmission::where('report_id', $report->id)->delete();
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
        
                // Update Comments
                AntemortemComment::where('report_id', $report->id)->delete();
                if (!empty($request->comment_text)) {
                    foreach ($request->comment_text as $commentText) {
                        if (!empty($commentText)) {
                            AntemortemComment::create([
                                'report_id' => $report->id,
                                'comment_text' => $commentText,
                            ]);
                        }
                    }
                }
        
                DB::commit();
        
                return redirect()->route('antemortem.index')->with('success', 'Antemortem report updated successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Antemortem update error: ' . $e->getMessage());
        
                return redirect()->back()->with('error', 'Failed to update data. Error: ' . $e->getMessage());
            }
        }
        
        
        
        public function deleteAllData($id)
{
    try {
        DB::beginTransaction();

        $report = AntemortemMaster::findOrFail($id);

        AntemortemGeneralCondition::where('report_id', $report->id)->delete();
        AntemortemAnimalInspection::where('report_id', $report->id)->delete();
        AntemortemSampleSubmission::where('report_id', $report->id)->delete();
        AntemortemComment::where('report_id', $report->id)->delete();

        // Optionally delete the report itself (if needed)
        $report->delete();

        DB::commit();

        return redirect()->route('antemortem.index')->with('success', 'All data for this report has been deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error deleting data for report ID ' . $id . ': ' . $e->getMessage());

        return redirect()->back()->with('error', 'Failed to delete data. Error: ' . $e->getMessage());
    }
}

        
public function view($id)
{
    $report = AntemortemMaster::with(['animal',  'sampleType', 'comment'])->findOrFail($id);
    $generalConditions = AntemortemGeneralCondition::where('report_id', $report->id)->get();

    return view('antemortem-report.view', compact('report', 'generalConditions'));
}

public function print($id)
{
    $report = AntemortemMaster::with(['animal', 'sampleType', 'comment'])->findOrFail($id);
    $generalConditions = AntemortemGeneralCondition::where('report_id', $report->id)->get();

    return view('antemortem-report.print', compact('report', 'generalConditions'));
}

     
        
        
        

}

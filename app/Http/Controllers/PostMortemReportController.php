<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PostmortemMaster;
use App\Models\PostmortemAnimalDetails;
use App\Models\PostmortemOrganDetails;
use App\Models\PostmortemSamples;
use App\Models\PostmortemComments;
class PostMortemReportController extends Controller
{
    public function create()
    {
        return view('postmortem-report.create', [ 'invoice_no' => $this->invoice_no()]);
    }
    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('postmortem_no',Auth::user()->store_id=1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
        }

       
        
        public function store(Request $request)
        {
           
            $request->validate([
                'inspection_date' => 'required|date',
            ]);
        
            DB::beginTransaction(); 
        
            try {
               
                $postmortem = new PostmortemMaster();
                $postmortem->postmortem_no = $request->postmortem_no;
                $postmortem->inspection_date = $request->inspection_date;
                $postmortem->save();
        
                // Store Animal Details
                if (!empty($request->animal_type)) {
                    foreach ($request->animal_type as $key => $animal) {
                        PostmortemAnimalDetails::create([
                            'postmortem_id' => $postmortem->id,
                            'animal_type' => $animal,
                            'carcasses_approved' => $request->carcasses_approved[$key] ?? null,
                            'carcasses_held' => $request->carcasses_held[$key] ?? null,
                            'carcasses_condemned' => $request->carcasses_condemned[$key] ?? null,
                        ]);
                    }
                }
        
                // Store Organ Details
                if (!empty($request->oragan_type)) {
                    foreach ($request->oragan_type as $key => $organ) {
                        PostmortemOrganDetails::create([
                            'postmortem_id' => $postmortem->id,
                            'organ_type' => $organ,
                            'organs_approved' => $request->organs_approved[$key] ?? null,
                            'organs_held' => $request->organs_held[$key] ?? null,
                            'organs_condemned' => $request->organs_condemned[$key] ?? null,
                        ]);
                    }
                }
        
                // Store Sample Submission
                if (!empty($request->sample_identification_type)) {
                    foreach ($request->sample_identification_type as $key => $sampleType) {
                        PostmortemSamples::create([
                            'postmortem_id' => $postmortem->id,
                            'sample_identification_type' => $sampleType,
                            'sample_location' => $request->sample_location[$key] ?? null,
                            'hold_tag' => $request->hold_tag[$key] ?? null,
                            'date_submitted' => $request->date_submitted[$key] ?? null,
                        ]);
                    }
                }
        
                // Store Comments
                if (!empty($request->comments)) {
                    foreach ($request->comments as $comment) {
                        if (!empty($comment)) {
                            PostmortemComments::create([
                                'postmortem_id' => $postmortem->id,
                                'comment' => $comment,
                            ]);
                        }
                    }
                }
        
                DB::commit(); 
        
                return redirect()->route('postmortem.index')->with('success', 'Postmortem Report saved successfully!');
            } catch (\Exception $e) {
                DB::rollback(); 
                \Log::error('postmortem store error: ' . $e->getMessage());

                return redirect()->back()->with('error', 'Failed to save Postmortem Report: ' . $e->getMessage());
            }
        }
        
        
}

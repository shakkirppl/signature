<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PostMortemMaster;
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
            // Validate input
            $request->validate([
                'inspection_date' => 'required|date',
                'postmortem_no'=> 'required',
            ]);
        
            // Start a database transaction
            DB::beginTransaction();
        
            try {
                // Insert into PostMortemMaster
                $postmortem = PostMortemMaster::create([
                    'postmortem_no' => $request->postmortem_no,
                    'inspection_date' => $request->inspection_date,
                    'user_id' => Auth::id(),
                    'store_id' => 1, // Change as needed
                ]);
        
                // Insert into PostmortemAnimalDetails
                if ($request->has('animal_type')) {
                    foreach ($request->animal_type as $index => $animal) {
                        PostmortemAnimalDetails::create([
                            'postmortem_id' => $postmortem->id,
                            'animal_type' => $animal,
                            'carcasses_approved' => $request->carcasses_approved[$index] ?? null,
                            'carcasses_held' => $request->carcasses_held[$index] ?? null,
                            'carcasses_condemned' => $request->carcasses_condemned[$index] ?? null,
                            'user_id' => Auth::id(),
                            'store_id' => 1,
                        ]);
                    }
                }
        
                // Insert into PostmortemOrganDetails
                if ($request->has('oragan_type')) {
                    foreach ($request->oragan_type as $index => $organ) {
                        PostmortemOrganDetails::create([
                            'postmortem_id' => $postmortem->id,
                            'organ_type' => $organ,
                            'organs_approved' => $request->organs_approved[$index] ?? null,
                            'organs_held' => $request->organs_held[$index] ?? null,
                            'organs_condemned' => $request->organs_condemned[$index] ?? null,
                            'user_id' => Auth::id(),
                            'store_id' => 1,
                        ]);
                    }
                }
        
                // Insert into PostmortemSamples
                if ($request->has('sample_identification_type')) {
                    foreach ($request->sample_identification_type as $index => $sample) {
                        PostmortemSamples::create([
                            'postmortem_id' => $postmortem->id,
                            'sample_identification_type' => $sample,
                            'sample_location' => $request->sample_location[$index] ?? null,
                            'hold_tag' => $request->hold_tag[$index] ?? null,
                            'date_submitted' => $request->date_submitted[$index] ?? null,
                            'user_id' => Auth::id(),
                            'store_id' => 1,
                        ]);
                    }
                }
        
                // Insert into PostmortemComments
                if ($request->has('comments')) {
                    foreach ($request->comments as $comment) {
                        if (!empty($comment)) {
                            PostmortemComments::create([
                                'postmortem_id' => $postmortem->id,
                                'comment' => $comment,
                                'user_id' => Auth::id(),
                                'store_id' => 1,
                            ]);
                        }
                    }
                }
        
              
                DB::commit();
        
                return redirect()->route('postmortem.create')->with('success', 'PostMortem Report saved successfully.');
        
            } catch (\Exception $e) {
               
                DB::rollBack();
                \Log::error('postmortem store error: ' . $e->getMessage());

                return back()->withErrors(['error' => 'Failed to save PostMortem Report: ' . $e->getMessage()]);
            }
        }
        
        
        
}

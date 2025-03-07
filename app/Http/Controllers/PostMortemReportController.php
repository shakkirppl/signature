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

       
        public function index()
{
    $postmortems = PostMortemMaster::orderBy('created_at', 'desc')->get();

    return view('postmortem-report.index', compact('postmortems'));
}

       
        
        public function store(Request $request)
        {
            // Validate input
           // Validate input
$request->validate([
    'inspection_date' => 'required|date',
    'postmortem_no' => 'required',
]);

DB::beginTransaction();

try {
    InvoiceNumber::updateinvoiceNumber('postmortem_no', 1);

    $postmortem = PostMortemMaster::create([
        'postmortem_no' => $request->postmortem_no,
        'inspection_date' => $request->inspection_date,
        'user_id' => Auth::id(),
        'store_id' => 1,
    ]);

    // Insert Postmortem Animal Details
    if (!empty($request->animal_type) && is_array($request->animal_type)) {
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

    // Insert Postmortem Organ Details
    if (!empty($request->organ_type) && is_array($request->organ_type)) {
        foreach ($request->organ_type as $index => $organ) {
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

    // Insert Postmortem Samples
    if (!empty($request->sample_identification_type) && is_array($request->sample_identification_type)) {
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

    // Insert Postmortem Comments
    if (!empty($request->comments) && is_array($request->comments)) {
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
        
                return redirect()->route('postmortem.index')->with('success', 'PostMortem Report saved successfully.');
        
            } catch (\Exception $e) {
               
                DB::rollBack();
                \Log::error('postmortem store error: ' . $e->getMessage());

                return back()->withErrors(['error' => 'Failed to save PostMortem Report: ' . $e->getMessage()]);
            }
        }


        public function edit($id)
        {
            $postmortem = PostMortemMaster::with(['animals', 'organs', 'samples', 'comments'])->findOrFail($id);
        
            return view('postmortem-report.edit', compact('postmortem'));
        }
        


     
        public function update(Request $request, $id)
        {
            // Validate input
            $request->validate([
                'inspection_date' => 'required|date',
                'postmortem_no' => 'required',
            ]);
        
            // Start a database transaction
            DB::beginTransaction();
        
            try {
                $postmortem = PostMortemMaster::findOrFail($id);
                $postmortem->update([
                    'inspection_date' => $request->inspection_date,
                ]);
        
                // Update or create PostmortemAnimalDetails
                if ($request->has('animal_type')) {
                    foreach ($request->animal_type as $index => $animal) {
                        PostmortemAnimalDetails::updateOrCreate(
                            [
                                'postmortem_id' => $postmortem->id,
                                'animal_type' => $animal,
                            ],
                            [
                                'carcasses_approved' => $request->carcasses_approved[$index] ?? null,
                                'carcasses_held' => $request->carcasses_held[$index] ?? null,
                                'carcasses_condemned' => $request->carcasses_condemned[$index] ?? null,
                                'user_id' => Auth::id(),
                                'store_id' => 1,
                            ]
                        );
                    }
                }
        
                // Update or create PostmortemOrganDetails
                if ($request->has('organ_type')) {
                    foreach ($request->organ_type as $index => $organ) {
                        PostmortemOrganDetails::updateOrCreate(
                            [
                                'postmortem_id' => $postmortem->id,
                                'organ_type' => $organ,
                            ],
                            [
                                'organs_approved' => $request->organs_approved[$index] ?? null,
                                'organs_held' => $request->organs_held[$index] ?? null,
                                'organs_condemned' => $request->organs_condemned[$index] ?? null,
                                'user_id' => Auth::id(),
                                'store_id' => 1,
                            ]
                        );
                    }
                }
        
                // Update or create PostmortemSamples
                if ($request->has('sample_identification_type')) {
                    foreach ($request->sample_identification_type as $index => $sample) {
                        PostmortemSamples::updateOrCreate(
                            [
                                'postmortem_id' => $postmortem->id,
                                'sample_identification_type' => $sample,
                            ],
                            [
                                'sample_location' => $request->sample_location[$index] ?? null,
                                'hold_tag' => $request->hold_tag[$index] ?? null,
                                'date_submitted' => $request->date_submitted[$index] ?? null,
                                'user_id' => Auth::id(),
                                'store_id' => 1,
                            ]
                        );
                    }
                }
        
                // Update or create PostmortemComments
                if ($request->has('comments')) {
                    foreach ($request->comments as $comment) {
                        if (!empty($comment)) {
                            PostmortemComments::updateOrCreate(
                                [
                                    'postmortem_id' => $postmortem->id,
                                ],
                                [
                                    'comment' => $comment,
                                    'user_id' => Auth::id(),
                                    'store_id' => 1,
                                ]
                            );
                        }
                    }
                }
        
                DB::commit();
        
                return redirect()->route('postmortem.index')->with('success', 'PostMortem Report updated successfully.');
        
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Postmortem update error: ' . $e->getMessage());
        
                return back()->withErrors(['error' => 'Failed to update PostMortem Report: ' . $e->getMessage()]);
            }
        }
        


    
public function print($id)
{
    $postmortem = PostMortemMaster::with('animals', 'organs', 'samples', 'comments')->findOrFail($id);
    return view('postmortem-report.print', compact('postmortem'));
}

        
}

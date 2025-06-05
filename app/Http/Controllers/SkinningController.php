<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Employee;
use App\Models\Product;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\SkinningMaster;
use App\Models\SkinningDetail;
use Illuminate\Support\Facades\Log;
use App\Models\ActionHistory;



class SkinningController extends Controller
{
    public function create()
    {
        
        $shipments = Shipment::where('shipment_status', 0)->get();
        $employees = Employee::where('designation_id', '2')->get();
        $products = Product::all();

        return view('skinning.create',['invoice_no'=>$this->invoice_no()], compact('shipments', 'employees', 'products'));
    }

    // public function index()
    // {
    //     $skinningRecords = SkinningMaster::with(['shipment', 'skinningDetails.employee'])
    //         ->orderBy('date', 'desc')
    //         ->get();
    
    //     return view('skinning.index', compact('skinningRecords'));
    // }

    public function index()
{
    $skinningRecords = SkinningMaster::with(['shipment', 'skinningDetails.employee'])
        ->whereHas('shipment', function ($query) {
            $query->where('shipment_status', 0);
        })
        ->orderBy('date', 'desc')
        ->get();

    return view('skinning.index', compact('skinningRecords'));
}

    
    public function invoice_no(){
        try {
             
         return $invoice_no =  InvoiceNumber::ReturnInvoice('skinning_code',1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }
                 public function store(Request $request)
                 {
                     $request->validate([
                         'skinning_code' => 'required|unique:skinning_master,skinning_code',
                         'date' => 'required|date',
                         'shipment_id' => 'required|exists:shipment,id',
                         'employees' => 'required|array',
                         'employees.*' => 'exists:employee,id',
                         'products' => 'required|array',
                         'products.*' => 'exists:product,id',
                         'quandity' => 'required|array',
                         'quandity.*' => 'numeric|min:1',
                         'damaged_quandity' => 'nullable|array',
                         'damaged_quandity.*' => 'numeric|min:0',
                         'skin_percentage' => 'required|array',
                         'skin_percentage.*' => 'string|min:0|max:100',
                     ]);
                 
                     try {
                         DB::beginTransaction();
                         $currentTime = Carbon::now()->format('H:i:s'); 
                 
                         // Create Skinning Master Entry (Only One per skinning_code)
                         $skinningMaster = SkinningMaster::create([
                             'skinning_code' => $request->skinning_code,
                             'date' => $request->date,
                             'time' => $currentTime, 
                             'shipment_id' => $request->shipment_id,
                             'user_id' => auth()->id(),
                             'store_id' => 1,
                             'status' => 1,
                         ]);
                 
                         // Loop through Employees and Store Each One with Their Product Details
                         foreach ($request->employees as $index => $employee_id) {
                             SkinningDetail::create([
                                 'skinning_id' => $skinningMaster->id,
                                 'employee_id' => $employee_id,
                                 'product_id' => $request->products[$index],
                                 'damaged_quandity'=> $request->damaged_quandity[$index],
                                 'quandity' => $request->quandity[$index],
                                 'skin_percentage' => $request->skin_percentage[$index],
                                 'store_id' => 1,
                             ]);
                         }
                         InvoiceNumber::updateinvoiceNumber('skinning_code',1);

                         DB::commit();
                         return redirect()->route('skinning.index')->with('success', 'Skinning record created successfully!');
                     } catch (\Exception $e) {
                         DB::rollBack();
                         return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
                     }
                 }
                 
                 
                 

public function edit($id)
{
    $skinning = SkinningMaster::with('skinningDetails')->findOrFail($id);
    $shipments = Shipment::where('shipment_status', 0)->get();
    $employees = Employee::where('designation_id', '2')->get();
    $products = Product::all();

    return view('skinning.edit', compact('skinning', 'shipments', 'employees', 'products'));
}


public function update(Request $request, $id)
{
    
    $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'shipment_id' => 'required|exists:shipment,id',
        'employees.*' => 'required|exists:employee,id',
        'products.*' => 'required|exists:product,id',
        'quandity.*' => 'required|integer|min:1',
       'damaged_quandity.*'=> 'nullable|integer',
    ]);

    
    DB::beginTransaction();

    try {
        $skinning = SkinningMaster::findOrFail($id);
        $skinning->date = $request->date;
        $skinning->time = $request->time;
        $skinning->shipment_id = $request->shipment_id;
        $skinning->save();

        $skinning->skinningDetails()->delete();

       
        foreach ($request->employees as $index => $employee_id) {
            SkinningDetail::create([
                'skinning_id' => $skinning->id,
                'employee_id' => $employee_id,
                'product_id' => $request->products[$index],
                'quandity' => $request->quandity[$index],
                'damaged_quandity'=> $request->damaged_quandity[$index],
                'skin_percentage' => $request->skin_percentage[$index],
                'store_id' => 1, 
            ]);
        }

      
        DB::commit();

        
        return redirect()->route('skinning.index')->with('success', 'Skinning record updated successfully.');

    } catch (\Exception $e) {
        
        DB::rollBack();
        return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()]);
    }
}


public function view($id)
{
    
    $skinning = SkinningMaster::with('skinningDetails.product', 'skinningDetails.employee')->findOrFail($id);

   
    return view('skinning.view', compact('skinning'));
}

public function destroy($id)
{
    try {
        
        $skinning = SkinningMaster::where('id', $id)->first();


        if (!$skinning) {
            return redirect()->route('skinning.index')->with('error', 'Record not found!');
        }

        SkinningDetail::where('skinning_id', $id)->delete();

        $skinning->delete();
        InvoiceNumber::decreaseInvoice('skinning_code', 1);

        return redirect()->route('skinning.index')->with('success', 'Sales order and its details have been deleted successfully!');
    } catch (\Exception $e) {
        return redirect()->route('skinning.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}

 

public function report(Request $request)
{
    $query = SkinningMaster::with(['shipment', 'skinningDetails.employee'])
        ->orderBy('date', 'desc');

   
    if ($request->has('shipment_id') && $request->shipment_id) {
        $query->where('shipment_id', $request->shipment_id);
    }

    
    if ($request->has('employee_id') && $request->employee_id) {
        $query->whereHas('skinningDetails', function ($q) use ($request) {
            $q->where('employee_id', $request->employee_id);
        });
    }

   
    if ($request->has('from_date') && $request->from_date) {
        $query->whereDate('date', '>=', $request->from_date);
    }
    if ($request->has('to_date') && $request->to_date) {
        $query->whereDate('date', '<=', $request->to_date);
    }

    $skinningRecords = $query->get();
    $employees = Employee::where('designation_id', 1)->get();
    $shipments = Shipment::all();

    return view('skinning.report', compact('skinningRecords', 'shipments', 'employees'));
}


public function requestDelete($id)
{
    $skinning = SkinningMaster::findOrFail($id);

    // Only allow if not already requested
    if ($skinning->delete_status == 0) {
        $skinning->delete_status = 1;
        $skinning->save();
        return redirect()->route('skinning.index')->with('success', 'Delete request sent for approval.');
    }

    return redirect()->route('skinning.index')->with('error', 'Delete request already sent.');
}

public function pendingDeleteRequests()
{
    $pendingSkinnings = SkinningMaster::where('delete_status', 1)->get();
    return view('skinning.pending-delete', compact('pendingSkinnings'));
}

public function editRequest($id)
{
    $skinning = SkinningMaster::with('skinningDetails')->findOrFail($id);
    $shipments = Shipment::where('shipment_status', 0)->get();
    $employees = Employee::where('designation_id', '2')->get();
    $products = Product::all();

    return view('skinning.edit-request', compact('skinning', 'shipments', 'employees', 'products'));
}


public function submitEditRequest(Request $request, $id)
{
    $skinning = SkinningMaster::with('skinningDetails')->findOrFail($id);

    $data = $request->validate([
        'date' => 'required|date',
        'time' => 'required',
        'shipment_id' => 'required|exists:shipment,id',
        'employees' => 'required|array',
        'employees.*' => 'exists:employee,id',
        'products' => 'required|array',
        'products.*' => 'exists:product,id',
        'quandity' => 'required|array',
        'quandity.*' => 'numeric|min:1',
        'damaged_quandity' => 'nullable|array',
        'damaged_quandity.*' => 'numeric|min:0',
        'skin_percentage' => 'required|array',
        'skin_percentage.*' => 'string|min:0|max:100',
    ]);

    // Only store changed main fields
    $editData = [];
    foreach (['date', 'time', 'shipment_id'] as $field) {
        if (isset($data[$field])) {
            $editData[$field] = $data[$field];
        }
    }

    $existingDetails = $skinning->skinningDetails->keyBy('employee_id');
    $detailChanges = [];
    $submittedEmployeeIds = [];

    foreach ($data['employees'] as $index => $employeeId) {
        $submittedEmployeeIds[] = $employeeId;
        
        $productId = $data['products'][$index];
        $quandity = $data['quandity'][$index];
        $damagedQuandity = $data['damaged_quandity'][$index] ?? 0;
        $skinPercentage = $data['skin_percentage'][$index];

        if ($existingDetails->has($employeeId)) {
            $old = $existingDetails[$employeeId];
            $detailChange = [
                'employee_id' => $employeeId,
                'old_employee_id' => $employeeId,
                'product_id' => $productId,
                'old_product_id' => $old->product_id,
            ];
            
            $hasChanges = false;

            // Check each field for changes
            if ($old->quandity != $quandity) {
                $detailChange['old_quandity'] = $old->quandity;
                $detailChange['quandity'] = $quandity;
                $hasChanges = true;
            }
            
            if ($old->damaged_quandity != $damagedQuandity) {
                $detailChange['old_damaged_quandity'] = $old->damaged_quandity;
                $detailChange['damaged_quandity'] = $damagedQuandity;
                $hasChanges = true;
            }
            
            if ($old->skin_percentage != $skinPercentage) {
                $detailChange['old_skin_percentage'] = $old->skin_percentage;
                $detailChange['skin_percentage'] = $skinPercentage;
                $hasChanges = true;
            }

            if ($hasChanges || ($old->product_id != $productId)) {
                $detailChanges[] = $detailChange;
            }
        } else {
            // New detail addition
            $detailChanges[] = [
                'employee_id' => $employeeId,
                'old_employee_id' => null,
                'product_id' => $productId,
                'quandity' => $quandity,
                'damaged_quandity' => $damagedQuandity,
                'skin_percentage' => $skinPercentage,
            ];
        }
    }

    // Check for deleted details
    foreach ($existingDetails as $employeeId => $detail) {
        if (!in_array($employeeId, $submittedEmployeeIds)) {
            $detailChanges[] = [
                'employee_id' => null,
                'old_employee_id' => $employeeId,
                'old_product_id' => $detail->product_id,
                'old_quandity' => $detail->quandity,
                'old_damaged_quandity' => $detail->damaged_quandity,
                'old_skin_percentage' => $detail->skin_percentage,
            ];
        }
    }

    $skinning->edit_request_data = json_encode([
        'main' => $editData,
        'details' => $detailChanges,
    ]);

    $skinning->edit_status = 'pending';
    $skinning->save();

    ActionHistory::create([
        'page_name' => 'Skinning',
        'record_id' => $skinning->id . '-' . $skinning->skinning_code,
        'action_type' => 'edit_requested',
        'user_id' => Auth::id(),
        'changes' => json_encode([
            'main' => $editData,
            'details' => $detailChanges,
        ]),
    ]);

    return redirect()->route('skinning.index')->with('success', 'Edit request submitted.');
}

public function approveEditRequest($id)
{
    $skinning = SkinningMaster::with('skinningDetails')->findOrFail($id);

    if ($skinning->edit_status !== 'pending' || !$skinning->edit_request_data) {
        return back()->with('error', 'No pending edit request found or request already processed.');
    }

    $editData = json_decode($skinning->edit_request_data, true);

    DB::beginTransaction();
    try {
        // Update main fields if they exist in the edit data
        if (isset($editData['main'])) {
            $skinning->update($editData['main']);
        }

        // Process detail changes
        $processedDetailIds = [];
        $detailChanges = $editData['details'] ?? [];

        foreach ($detailChanges as $detailEdit) {
            $employeeId = $detailEdit['employee_id'] ?? null;
            $oldEmployeeId = $detailEdit['old_employee_id'] ?? $employeeId;

            // Handle detail modifications and additions
            if ($employeeId !== null) {
                $existingDetail = $skinning->skinningDetails->firstWhere('employee_id', $oldEmployeeId);

                if ($existingDetail) {
                    // Update existing detail
                    $existingDetail->update([
                        'employee_id' => $employeeId,
                        'product_id' => $detailEdit['product_id'] ?? $existingDetail->product_id,
                        'quandity' => $detailEdit['quandity'] ?? $existingDetail->quandity,
                        'damaged_quandity' => $detailEdit['damaged_quandity'] ?? $existingDetail->damaged_quandity,
                        'skin_percentage' => $detailEdit['skin_percentage'] ?? $existingDetail->skin_percentage,
                    ]);
                    $processedDetailIds[] = $existingDetail->id;
                } else {
                    // Add new detail
                    $newDetail = SkinningDetail::create([
                        'skinning_id' => $skinning->id,
                        'employee_id' => $employeeId,
                        'product_id' => $detailEdit['product_id'],
                        'quandity' => $detailEdit['quandity'],
                        'damaged_quandity' => $detailEdit['damaged_quandity'],
                        'skin_percentage' => $detailEdit['skin_percentage'],
                        'store_id' => 1,
                    ]);
                    $processedDetailIds[] = $newDetail->id;
                }
            } else {
                // Handle detail deletions (where employee_id is null)
                $skinning->skinningDetails()
                    ->where('employee_id', $oldEmployeeId)
                    ->delete();
            }
        }

        // Clean up and finalize
        $skinning->edit_status = 'approved';
        $skinning->edit_request_data = null;
        $skinning->save();

        // Log the approval
        ActionHistory::create([
            'page_name' => 'Skinning',
            'record_id' => $skinning->id . '-' . $skinning->skinning_code,
            'action_type' => 'edit_approved',
            'user_id' => Auth::id(),
            'changes' => json_encode($editData),
        ]);

        DB::commit();

        return redirect()->route('skinning.index')
            ->with('success', 'Edit request approved successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error approving edit request: ' . $e->getMessage());
        
        return back()->with('error', 'Failed to approve edit request. Please try again.');
    }
}

public function pendingEditRequests()
{
    $skinnings = SkinningMaster::with(['skinningDetails', 'shipment'])
        ->where('edit_status', 'pending')
        ->get();

    foreach ($skinnings as $skinning) {
        $editData = json_decode($skinning->edit_request_data, true);
        $changes = [];

        if ($editData) {
            // Main fields
            if (isset($editData['main'])) {
                foreach ($editData['main'] as $key => $newVal) {
                    $changes[$key] = [
                        'original' => $skinning->$key,
                        'requested' => $newVal,
                    ];
                }
            }

            // Detail changes
            if (isset($editData['details'])) {
                $changes['details'] = [];

                foreach ($editData['details'] as $detailEdit) {
                    $detailChanges = [];

                    $employeeId = $detailEdit['employee_id'] ?? null;
                    $oldEmployeeId = $detailEdit['old_employee_id'] ?? null;

                    // Employee ID change
                    if ($oldEmployeeId != $employeeId) {
                        $detailChanges['employee_id'] = [
                            'original' => $oldEmployeeId,
                            'requested' => $employeeId,
                        ];
                    } else {
                        $detailChanges['employee_id'] = $employeeId;
                    }

                    // Product ID change
                    if (isset($detailEdit['product_id'])) {
                        $detailChanges['product_id'] = [
                            'original' => $detailEdit['old_product_id'] ?? null,
                            'requested' => $detailEdit['product_id'],
                        ];
                    }

                    // Handle other field changes
                    foreach (['quandity', 'damaged_quandity', 'skin_percentage'] as $field) {
                        if (
                            isset($detailEdit[$field]) &&
                            (!isset($detailEdit['old_' . $field]) || $detailEdit[$field] != $detailEdit['old_' . $field])
                        ) {
                            $detailChanges[$field] = [
                                'original' => $detailEdit['old_' . $field] ?? null,
                                'requested' => $detailEdit[$field] ?? null,
                            ];
                        }
                    }

                    if (!empty($detailChanges)) {
                        $changes['details'][] = $detailChanges;
                    }
                }
            }

            $skinning->changed_fields = $changes;
        }
    }

    return view('skinning.pending-edit-request', compact('skinnings'));
}

public function rejectEdit($id)
{
    $skinning = SkinningMaster::findOrFail($id);
    
    if ($skinning->edit_status !== 'pending') {
        return back()->with('error', 'No pending edit request found or request already processed.');
    }

    $editData = json_decode($skinning->edit_request_data, true);

    $skinning->edit_status = 'rejected';
    $skinning->edit_request_data = null;
    $skinning->save();

    ActionHistory::create([
        'page_name' => 'Skinning',
        'record_id' => $skinning->id . '-' . $skinning->skinning_code,
        'action_type' => 'edit_rejected',
        'user_id' => Auth::id(),
        'changes' => json_encode($editData),
    ]);

    return redirect()->route('skinning.index')
        ->with('success', 'Edit request rejected successfully.');
}



}

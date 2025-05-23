<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankMaster;
use Illuminate\Support\Facades\Log;
use App\Models\AccountHead;
class BankMasterController extends Controller
{
    public function index()
    {
        try {
            $bankMasters = BankMaster::all(); 
            return view('bank-master.index', compact('bankMasters'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



    public function create() 
    {
        try {
        return view('bank-master.create');
    } catch (\Exception $e) {
        return $e->getMessage();
      }
    }
   
    public function store(Request $request)
    {
        try {
            // Validate the form data
            $request->validate([
                'code' => 'required|string|max:255',
                'bank_name' => 'required|string|max:255',
                'currency' => 'required|string|max:255',
                'gl' => 'nullable|string|max:255', 
                'account_no' => 'nullable|string|max:255', 
                'account_name' => 'nullable|string|max:255', 
                'type' => 'nullable|string|max:255', 
            ]);
    
            // Append currency to bank name
            $currency = strtoupper($request->currency);
            $fullBankName = $currency === 'USD' ? $request->bank_name . ' - ' . $currency : $request->bank_name;
                
            // Create Account Head
            $accountHead = AccountHead::create([
                'name' => $fullBankName,
                'parent_id' => '152',
                'opening_balance' => null,
                'dr_cr' => null,
                'can_delete' => 1,
            ]);
    
            // Create Bank Master record
            BankMaster::create([
                'code' => $request->input('code'),
                'bank_name' => $fullBankName,
                'currency' => $request->input('currency'),
                'type' => $request->input('type'),
                'gl' => $request->input('gl'),
                'account_no' => $request->input('account_no'),
                'account_name' => $request->input('account_name'),
                'store_id' => 1,
                'user_id' => auth()->id(),
                'account_head_id' => $accountHead->id,
            ]);
    
            return redirect()->route('bank-master.index')->with('success', 'Bank Master record created successfully.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    

    public function edit($id)
    {
        try {
            $bankMaster = BankMaster::findOrFail($id);
            return view('bank-master.edit', compact('bankMaster'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'code' => 'required|string|max:255',
                'bank_name' => 'required|string|max:255',
                'currency' => 'required|string|max:255',
                'gl' => 'nullable|string|max:255',
                'account_no' => 'nullable|string|max:255', 
                'account_name' => 'nullable|string|max:255', 
                'type' => 'nullable|string|max:255', 
            ]);
    
            // Find existing record
            $bankMaster = BankMaster::findOrFail($id);
    
            // Append currency to bank name
            $currency = strtoupper($request->currency);
            $fullBankName = $currency === 'USD' ? $request->bank_name . ' - ' . $currency : $request->bank_name;    
            // Update fields
            $bankMaster->update([
                'code' => $request->input('code'),
                'bank_name' => $fullBankName,
                'currency' => $request->input('currency'),
                'type' => $request->input('type'),
                'gl' => $request->input('gl'),
                'account_no' => $request->input('account_no'),
                'account_name' => $request->input('account_name'),
            ]);
    
            return redirect()->route('bank-master.index')->with('success', 'Bank Master record updated successfully.');
        } 
        catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Bank Master Update Validation Error', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors());
        } 
        catch (\Exception $e) {
            Log::error('Bank Master Update Error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return redirect()->back()->with('error', 'An error occurred while updating the record.');
        }
    }
    

    public function destroy($id)
    {
        try {
            $bankMaster = BankMaster::findOrFail($id);
            $bankMaster->delete();
            return redirect()->route('bank-master.index')->with('success');
        } catch (\Exception $e) {
            return redirect()->route('bank-master.index')->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }
    
}

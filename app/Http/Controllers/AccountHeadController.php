<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use Illuminate\Http\Request;
use App\Models\AccountTransactions;
use Carbon\Carbon;

class AccountHeadController extends Controller
{
    // Display all account heads in hierarchical format
    public function index()
    {
        $accountHeads = AccountHead::with('children')->whereNull('parent_id')->get();
        return view('account_heads.index', compact('accountHeads'));
    }
    public function create(Request $request)
{
    $parentId = $request->query('parent_id');
    $accountHeads = AccountHead::all(); 
    return view('account_heads.create', compact('parentId', 'accountHeads'));
}
    // Store a new account head
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:account_heads,id',
            'opening_balance' => 'nullable|numeric',
            'dr_cr' => 'nullable|in:Dr,Cr',
            
        ]);
    
        $accountHead = new AccountHead();
        $accountHead->name = $validated['name'];
        $accountHead->parent_id = $validated['parent_id'] ?? null;
        $accountHead->opening_balance = $validated['opening_balance'] ?? 0; // Default to 0 if not provided
        $accountHead->dr_cr = $validated['dr_cr'] ?? null;
       
        $accountHead->save();
    
        // Store transaction if opening balance > 0
        if ($accountHead->opening_balance > 0) {
            $group_no = AccountTransactions::orderBy('id','desc')->max('group_no');
            $group_no+=1;
            $accountHead->date = $request->date ? Carbon::parse($request->date)->toDateString() : Carbon::now()->toDateString();            if ($accountHead->dr_cr == 'Dr') {
                 AccountTransactions::storeTransaction($group_no,$accountHead->date,"20",$accountHead->id,$accountHead->id,"Opening  No:".$accountHead->name,"OpeningBalance",$accountHead->opening_balance ,null);
                 AccountTransactions::storeTransaction($group_no,$accountHead->date,$accountHead->id,$accountHead->id,"20","Opening  No:".$accountHead->name,"OpeningBalance",null,$accountHead->opening_balance);

            } else {
                 AccountTransactions::storeTransaction($group_no,$accountHead->date,"20",$accountHead->id,$accountHead->id,"Opening  No:".$accountHead->name,"OpeningBalance",null,$accountHead->opening_balance);
                 AccountTransactions::storeTransaction($group_no,$accountHead->date,$accountHead->id,$accountHead->id,"20","Opening  No:".$accountHead->name,"OpeningBalance",$accountHead->opening_balance,null);
            }
        }
    
        return redirect()->route('account-heads.index')->with('success', 'Account Head created successfully!');
    }
    
    
    public function edit($id)
    
    {
        $accountHead = AccountHead::findOrFail($id);
        return view('account_heads.edit', compact('accountHead'));
    }
    // Update an existing account head
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $accountHead = AccountHead::findOrFail($id);
        $accountHead->update([
            'name' => $request->name,
            
        ]);

        return redirect()->route('account-heads.index')->with('success', 'Account Head updated successfully!');
    }
    // Delete an account head
    public function destroy($id)
{
    $accountHead = AccountHead::findOrFail($id);
    $accountHead->delete();

    return response()->json(['success' => true, 'message' => 'Account head deleted successfully!']);
}

}

<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use Illuminate\Http\Request;

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
        ]);
    
        $accountHead = new AccountHead();
        $accountHead->name = $validated['name'];
        $accountHead->parent_id = $validated['parent_id'] ?? null;
        $accountHead->save();
    
        return redirect()->route('account-heads.index')
        ->with('success');  
    
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
            'name' => $validated['name'],
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

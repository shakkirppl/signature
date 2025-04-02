<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AccountHead;


class UserController extends Controller
{

    public function index()
    {
        $users = User::with('designation')->get(); 
        return view('users.index', compact('users'));
    }
    public function create()
    {
        $designations = Designation::all(); 
        return view('users.create', compact('designations'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'designation_id' => 'required|exists:employees_designation,id',
        ]);
        $accountHead = AccountHead::create([
            'name' => $request->name,
            'parent_id' => '200', 
            'opening_balance' => $request->opening_balance ?? 0,
            'dr_cr' => $request->opening_balance ? $request->dr_cr : null,
            'can_delete' => 1, 
        ]);


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'designation_id' => $request->designation_id,
            'account_head_id' => $accountHead->id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
{
    $user = User::findOrFail($id);
    $designations = Designation::all();
    return view('users.edit', compact('user', 'designations'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        'designation_id' => 'required|exists:employees_designation,id',
    ]);

    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'designation_id' => $request->designation_id,
    ]);

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}

}

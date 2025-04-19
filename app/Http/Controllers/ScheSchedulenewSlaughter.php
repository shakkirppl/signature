<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewSlaughterTime;
use Illuminate\Support\Facades\Auth;

class ScheSchedulenewSlaughter extends Controller
{
    public function create()
    {
        
    
        return view('new-slaughtertime.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
        ]);

        NewSlaughterTime::create([
            'date' => $request->date,
            'time' => $request->time,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('index.schedule')->with('success', 'Slaughter timing scheduled successfully.');

    }

    public function index()
{
    $timings = NewSlaughterTime::latest()->get(); // Eager load user if needed
    return view('new-slaughtertime.index', compact('timings'));
}
}

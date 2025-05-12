<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
   use App\Models\Inspection;


class AnimalReceivingNoteController extends Controller
{
    

// public function index()
// {
//     $inspections = Inspection::with('supplier')->latest()->get();

//     return view('animal_receive.index', compact('inspections'));
// }
public function index()
{
    $inspections = Inspection::with('supplier')
        ->where('weight_status', 1)
        ->latest()
        ->get();

    return view('animal_receive.index', compact('inspections'));
}


public function show($id)
{
    $inspection = Inspection::with(['details.product', 'supplier', 'shipment'])->findOrFail($id);
    return view('animal_receive.view', compact('inspection'));
}

public function print($id)
{
    $inspection = Inspection::with(['details.product', 'supplier', 'shipment'])->findOrFail($id);
    return view('animal_receive.print', compact('inspection'));
}


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsdToShilling;

class UsdToShillingController extends Controller
{
    public function index()
    {
        $currencies = UsdToShilling::all();
        return view('usd_to_shilling.index', compact('currencies'));
    }

    public function edit($id)
    {
        $currency = UsdToShilling::findOrFail($id);
        return view('usd_to_shilling.edit', compact('currency'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'usd' => 'required|numeric',
            'shilling' => 'required',
        ]);
    
        try {
            $currency = UsdToShilling::findOrFail($id);
            $currency->update([
                'usd' => $request->usd,
                'shilling' => str_replace(',', '', $request->shilling),
            ]);
    
            return redirect()->route('usd_to_shilling.index')->with('success', 'Currency updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Currency update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating currency: ' . $e->getMessage());
        }
    }
    
}

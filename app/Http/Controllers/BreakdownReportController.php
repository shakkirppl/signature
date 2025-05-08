<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakdownReport;
use Illuminate\Support\Facades\Auth;

class BreakdownReportController extends Controller
{
    public function create()
    {
        return view('breakdown-report.create');
    }
    public function index()
    {
        $reports = BreakdownReport::latest()->get();
        return view('breakdown-report.index', compact('reports'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'problem_reported' => 'nullable|string',
            'action_taken' => 'required|string',
            'equipment_id' => 'required|string',
            'time_out_of_service' => 'required|string',
        ]);

        BreakdownReport::create([
            'date' => $request->date,
            'problem_reported' => $request->problem_reported,
            'action_taken' => $request->action_taken,
            'equipment_id' => $request->equipment_id,
            'time_out_of_service' => $request->time_out_of_service,
            'user_id' => Auth::id(),
            'store_id' => 1,
        ]);

        return redirect()->route('breakdown-report.index')->with('success', 'Report saved successfully!');
    }

    public function edit($id)
    {
        $report = BreakdownReport::findOrFail($id);
        return view('breakdown-report.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $report = BreakdownReport::findOrFail($id);
        $request->validate([
            'date' => 'required|date',
            'problem_reported' => 'nullable|string',
            'action_taken' => 'required|string',
            'equipment_id' => 'required|string',
            'time_out_of_service' => 'required|string',
        ]);

        $report->update($request->all());

        return redirect()->route('breakdown-report.index')->with('success', 'Report updated successfully!');
    }

    public function destroy($id)
    {
        BreakdownReport::findOrFail($id)->delete();
        return redirect()->route('breakdown-report.index')->with('success', 'Report deleted successfully!');
    }
}
// 
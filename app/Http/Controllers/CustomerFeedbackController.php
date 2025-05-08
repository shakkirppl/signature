<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\CustomerFeedback;

use Illuminate\Http\Request;

class CustomerFeedbackController extends Controller
{
    public function create()
    {
        $customers = Customer::all();
        return view('customer-feedback.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'customer_id' => 'required|exists:customer,id',
            'feedback' => 'required|string',
        ]);

        $feedback = new CustomerFeedback();
        $feedback->date = $request->date;
        $feedback->customer_id = $request->customer_id;
        $feedback->feedback = $request->feedback;
        $feedback->user_id = auth()->user()->id; // Assign logged-in user's ID
        $feedback->save();
        return redirect()->back()->with('success', 'Feedback submitted successfully.');
    }

    public function index()
    {
        $feedbacks = CustomerFeedback::withTrashed()->get();

        return view('customer-feedback.index', compact('feedbacks'));
    }

    public function destroy($id)
    {
       
        $feedback = CustomerFeedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('customer-feedback.index')->with('success', 'Feedback deleted successfully.');
    }

}

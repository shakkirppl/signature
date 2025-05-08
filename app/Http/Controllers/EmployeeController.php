<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\InvoiceNumber;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;


class EmployeeController extends Controller
{
    public function create()
    {
        $designations = Designation::all(); 
        return view('employee-creation.create',['invoice_no'=>$this->invoice_no()], compact('designations'));
    }



    public function invoice_no(){
        try {
             
         return $invoice_no=InvoiceNumber::ReturnInvoice('employee_code', 1);
                  } catch (\Exception $e) {
         
            return $e->getMessage();
          }
                 }

               
    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|unique:employee,employee_code',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employee,email',
            'contact_number' => 'nullable|string|max:15',
            'designation_id' => 'required|exists:employees_designation,id',
        ]);

        Employee::create([
            'employee_code' => $request->employee_code,
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'designation_id' => $request->designation_id,
            'store_id' => 1, 
            'user_id' => auth()->id(),
        ]);
        InvoiceNumber::updateinvoiceNumber('employee_code',1);

        return redirect()->route('employee.index')->with('success', 'Employee created successfully.');
    }


    public function index()
{
    $employees = Employee::with('designation')->get(); 
    return view('employee-creation.index', compact('employees'));
}

public function edit($id)
{
    $employee = Employee::findOrFail($id);
    $designations = Designation::all();
    return view('employee-creation.edit', compact('employee', 'designations'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'employee_code' => 'required|unique:employee,employee_code,' . $id,
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:employee,email,' . $id,
        'contact_number' => 'nullable|string|max:15',
        'designation_id' => 'required|exists:employees_designation,id',
    ]);

    $employee = Employee::findOrFail($id);
    $employee->update([
        'employee_code' => $request->employee_code,
        'name' => $request->name,
        'email' => $request->email,
        'contact_number' => $request->contact_number,
        'designation_id' => $request->designation_id,
    ]);

    return redirect()->route('employee.index')->with('success');
}


public function destroy($id)
{
    try {
        $Employee = Employee::findOrFail($id);
        $Employee->delete();
        InvoiceNumber::decreaseInvoice('employee_code', 1);
        return redirect()->route('employee.index')->with('success');
    } catch (\Exception $e) {
        return redirect()->route('employee.index')->with('error', 'Error deleting record: ' . $e->getMessage());
    }
}


}

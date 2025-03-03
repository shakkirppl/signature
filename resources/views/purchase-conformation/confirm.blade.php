@extends('layouts.layout')
@section('content')

<style>
    #shipment_id:disabled {
    color: black !important; 
    background-color: #e9ecef !important; 
    opacity: 1 !important; 
    cursor: not-allowed;
}
.table {
    border-collapse: collapse;
    width: 40%;
}

button.remove-row {
    padding: 5px 10px;
}
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div><br />
                    @endif
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Purchase Confirmation</h4>
                    <form action="{{ route('purchase-conformation.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="inspection_id" value="{{ $inspection->id }}">
                        <input type="hidden" name="purchaseOrder_id" value="{{ $order->id }}">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="order_no" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="order_no" name="order_no" value="{{ $inspection->order_no }}" readonly>
                                
                            </div>
                            <div class="col-md-4">
                                <label for="invoice_number" class="form-label">Conformation Code:</label>
                                <input type="invoice_number" class="form-control" id="invoice_number" name="invoice_number" value="{{ $invoice_no }}" readonly>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select id="shipment_id" class="form-control" disabled>
                                       <option value="{{ $inspection->shipment->id ?? '' }}" >
                                            {{ $inspection->shipment->shipment_no ?? 'No Shipment Assigned' }}
                                 </option>
                               </select>
                                <input type="hidden" name="shipment_id" value="{{ $inspection->shipment->id ?? '' }}">

                            </div>
                            </div>
                            <div class="row mb-3">
 
                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label">Supplier:</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="{{ $inspection->supplier->id }}" selected>{{ $inspection->supplier->name }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="" required>
                            </div>
                           
                        </div>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Mark</th>
                                    <th>Male Accepted Quandity</th>
                                    <th>Female Accepted Quandity</th>
                                    <th>Total Accepted Quandity</th>
                                    <!-- <th>Rate</th>
                                    <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody id="product-details">
                                @foreach ($inspection->details as $index => $detail)
                                    <tr>
                                    <td>
                                      <select name="products[{{ $index }}][product_id]" class="form-control product-select" required style="width: 150px;">
                                          <option value="">Select Product</option>
                                              @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-rate="{{ $product->rate }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                   {{ $product->product_name }}
                                                </option>
                                              @endforeach
                                      </select>
                                      </td>

                                      
                                        <td>
                                           <input type="text" name="products[{{ $index }}][mark]" class="form-control qty" value="{{ $detail->mark }}" required style="width: 150px;">
                                       </td>

                                        <td>
                                            <input type="number" name="products[{{ $index }}][male_accepted_qty]" class="form-control qty" value="{{ $detail->male_accepted_qty }}" min="1" style="width: 150px;">
                                        </td>
                                        <td>
                                            <input type="number" name="products[{{ $index }}][female_accepted_qty]" class="form-control qty" value="{{ $detail->female_accepted_qty }}" min="1" style="width: 150px;">
                                        </td>
                                        <td>
                                            <input type="number" name="products[{{ $index }}][accepted_qty]" class="form-control accepted_qty" value="" style="width: 150px;" readonly>
                                            <input type="hidden" name="products[{{ $index }}][rate]" class="form-control rate" value="0" style="width: 200px;">
                                            <input type="hidden" name="products[{{ $index }}][total]" class="form-control total" value="0" readonly style="width: 200px;">
                                        </td>
                                       

                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
</div>
                         <br>
                        <div class="row">
   
<div class="col-md-5">
    <div class="table-responsive">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Expense</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="expense-details">
                <tr>
                    <td> <select name="expense_id[]" class="form-control expense-select"  style="width: 150px;">
                                          <option value="">Select Expenses</option>
                                              @foreach ($coa as $head)
                                                <option value="{{ $head->id }}" data-rate="{{ $head->rate }}" >
                                                   {{ $head->name }}
                                                </option>
                                              @endforeach
                                      </select>
                    </td>
              <td><input type="number" name="amount[]" class="form-control expense-amount" value="0"  style="width: 200px;"></td>
              <td><button type="button" class="btn btn-danger btn-sm remove-expense">X</button></td>
                </tr>
              
            </tbody>
        </table>
</div>
        <button type="button" id="add-expense" class="btn btn-secondary">Add Expense</button>

    </div>
    <div class="col-md-1"></div>

    <!-- Summary Section -->
    <div class="col-md-6">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="item_total" class="form-label">Item Amount:</label>
            <input type="number" id="item_total" name="item_total" class="form-control" value="{{ $inspection->details->sum(fn($d) => $d->accepted_qty * $d->rate) }}" >
        </div>
        <div class="col-md-4">
            <label for="total_expense" class="form-label">Expense Amount:</label>
            <input type="number" id="total_expense" name="total_expense" class="form-control" >
        </div>
        <div class="col-md-4">
            <label for="grand_total" class="form-label">Grand Total:</label>
            <input type="number" id="grand_total" name="grand_total" class="form-control" >
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="advance_amount" class="form-label">Advanced Amount:</label>
            <input type="number" id="advance_amount" name="advance_amount" class="form-control" value="{{ $order->advance_amount}}" >
        </div>
        <div class="col-md-6">
            <label for="balance_amount" class="form-label">Balance Amount:</label>
            <input type="number" id="balance_amount" name="balance_amount" class="form-control" >
        </div>
    </div>
</div>


                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    calculateTotals();


    document.querySelectorAll('#product-details').forEach(table => {
        table.addEventListener('input', function (event) {
            const target = event.target;

            if (target.classList.contains('qty') || target.classList.contains('rate')) {
                updateRowTotal(target.closest('tr'));
            }

            calculateTotals();
        });
    });

    // Event listener for expense table changes
    document.getElementById('expense-details').addEventListener('input', function (event) {
        if (event.target.classList.contains('expense-amount')) {
            calculateTotals();
        }
    });

    // Add event listener for the "Add Expense" button
    document.getElementById('add-expense').addEventListener('click', function () {
        addExpenseRow();
    });

    function addExpenseRow() {
        const expenseTable = document.getElementById('expense-details');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>
                <select name="expense_id[]" class="form-control expense-select" style="width: 150px;">
                    <option value="">Select Expenses</option>
                    ${getExpenseOptions()}
                </select>
            </td>
            <td>
                <input type="number" name="amount[]" class="form-control expense-amount" value="0" style="width: 100px;">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-expense">X</button>
            </td>
        `;

        expenseTable.appendChild(newRow);

        // Attach event listener to remove button
        newRow.querySelector('.remove-expense').addEventListener('click', function () {
            newRow.remove();
            calculateTotals();
        });
    }

    function getExpenseOptions() {
        let options = '';
        @foreach ($coa as $head)
            options += `<option value="{{ $head->id }}" data-rate="{{ $head->rate }}">{{ $head->name }}</option>`;
        @endforeach
        return options;
    }

    function updateRowTotal(row) {
        const maleQty = parseFloat(row.querySelector('[name*="[male_accepted_qty]"]').value) || 0;
        const femaleQty = parseFloat(row.querySelector('[name*="[female_accepted_qty]"]').value) || 0;
        const actualQtyField = row.querySelector('[name*="[accepted_qty]"]');
        const rateField = row.querySelector('.rate');
        const totalField = row.querySelector('.total');

        const actualQty = maleQty + femaleQty;
        actualQtyField.value = actualQty;

        const rate = parseFloat(rateField.value) || 0;
        totalField.value = (actualQty * rate).toFixed(2);
    }

    function calculateTotals() {
        let grandTotal = 0;

        // Calculate Item Total
        document.querySelectorAll('#product-details tr').forEach(row => {
            updateRowTotal(row);
            grandTotal += parseFloat(row.querySelector('.total').value) || 0;
        });

        let totalExpense = calculateExpenseTotal();
        
        // Update totals
        document.getElementById('item_total').value = grandTotal.toFixed(2);
        document.getElementById('total_expense').value = totalExpense.toFixed(2);
        document.getElementById('grand_total').value = (grandTotal + totalExpense).toFixed(2);

        const advanceAmount = parseFloat(document.getElementById('advance_amount').value) || 0;
        document.getElementById('balance_amount').value = (grandTotal  - advanceAmount).toFixed(2);
    }

    function calculateExpenseTotal() {
        let totalExpense = 0;
        document.querySelectorAll('.expense-amount').forEach(input => {
            totalExpense += parseFloat(input.value) || 0;
        });
        return totalExpense;
    }

    

});



</script>




@endsection

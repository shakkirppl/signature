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
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Purchase Confirmation</h4>
                    <form action="{{ route('purchase-conformation.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="inspection_id" value="{{ $inspection->id }}">
                        
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
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $inspection->date }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label">Supplier:</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="{{ $inspection->supplier->id }}" selected>{{ $inspection->supplier->name }}</option>
                                </select>
                            </div>
                           
                        </div>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Mark</th>
                                    <th>Accepted Quandity</th>
                                    <th>Rate</th>
                                    <th>Total</th>
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
                                            <select name="products[{{ $index }}][type]" class="form-control" required  style="width: 150px;">
                                                <option value="Male" {{ $detail->type == 'Male' ? 'selected' : '' }}>Male</option>
                                               <option value="Female" {{ $detail->type == 'Female' ? 'selected' : '' }}>Female</option>
                                           </select>
                                      </td>
                                        <td>
                                           <input type="text" name="products[{{ $index }}][mark]" class="form-control qty" value="" required style="width: 150px;">
                                       </td>

                                        <td>
                                            <input type="number" name="products[{{ $index }}][accepted_qty]" class="form-control qty" value="{{ $detail->accepted_qty }}" min="1" style="width: 150px;">
                                        </td>
                                        <td>
                                            <input type="number" name="products[{{ $index }}][rate]" class="form-control rate" value="{{ $detail->rate }}" style="width: 150px;">
                                        </td>
                                        <td>
                                            <input type="number" name="products[{{ $index }}][total]" class="form-control total" value="{{ $detail->actual_qty * $detail->rate }}" readonly style="width: 150px;">
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
              <td><input type="number" name="amount[]" class="form-control expense-amount" value="0"  style="width: 100px;"></td>
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
            <input type="number" id="item_total" name="item_total" class="form-control" value="{{ $inspection->details->sum(fn($d) => $d->actual_qty * $d->rate) }}" readonly>
        </div>
        <div class="col-md-4">
            <label for="total_expense" class="form-label">Expense Amount:</label>
            <input type="number" id="total_expense" name="total_expense" class="form-control" readonly>
        </div>
        <div class="col-md-4">
            <label for="grand_total" class="form-label">Grand Total:</label>
            <input type="number" id="grand_total" name="grand_total" class="form-control" readonly>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="advance_amount" class="form-label">Advanced Amount:</label>
            <input type="number" id="advance_amount" name="advance_amount" class="form-control" value="{{ optional($inspection->purchase_order)->advance_amount ?? 0 }}" readonly>
        </div>
        <div class="col-md-6">
            <label for="balance_amount" class="form-label">Balance Amount:</label>
            <input type="number" id="balance_amount" name="balance_amount" class="form-control" readonly>
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

    document.getElementById('add-expense').addEventListener('click', function () {
        addExpenseRow();
    });

    document.getElementById('expense-details').addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-expense')) {
            event.target.closest('tr').remove();
            calculateTotals();
        }
    });

    document.getElementById('expense-details').addEventListener('input', function (event) {
        if (event.target.classList.contains('expense-amount')) {
            calculateTotals();
        }
    });

    function addExpenseRow() {
        const tableBody = document.getElementById('expense-details');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td>
                <select name="expense_id[]" class="form-control expense-select" required>
                    <option value="">Select Expense</option>
                    ${generateExpenseOptions()}
                </select>
            </td>
            <td><input type="number" name="amount[]" class="form-control expense-amount" value="0" required></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-expense">X</button></td>
        `;

        tableBody.appendChild(newRow);
    }

    function generateExpenseOptions() {
        return `@foreach ($coa as $head)
                    <option value="{{ $head->id }}" data-rate="{{ $head->rate }}">{{ $head->name }}</option>
                @endforeach`;
    }

    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('#product-details tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const rate = parseFloat(row.querySelector('.rate').value) || 0;
            const total = qty * rate;
            row.querySelector('.total').value = total;
            grandTotal += total;
        });

        let expenseTotal = calculateExpenseTotal();
        document.getElementById('item_total').value = grandTotal;
        document.getElementById('grand_total').value = grandTotal + expenseTotal;

        const advancedAmount = parseFloat(document.getElementById('advance_amount').value) || 0;
        document.getElementById('balance_amount').value = grandTotal + expenseTotal - advancedAmount;
    }

    function calculateExpenseTotal() {
        let totalExpense = 0;
        document.querySelectorAll('.expense-amount').forEach(input => {
            totalExpense += parseFloat(input.value) || 0;
        });

        document.getElementById('total_expense').value = totalExpense;
        return totalExpense;
    }
});

</script>




@endsection

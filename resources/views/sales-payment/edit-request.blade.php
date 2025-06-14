@extends('layouts.layout')
@section('content')
<style>
.table {
    width: 100%; /* Ensures table fills the container */
    border-collapse: collapse;
}

.table th, .table td {
    padding: 5px;
    text-align: left;
    font-size: 14px; /* Adjust font size for better visibility */
}

input[type="text"], select {
    width: 100%; /* Makes inputs fully responsive */
    padding: 5px;
    font-size: 14px;
}

.table-responsive {
    overflow-x: auto; /* Allows horizontal scrolling if needed */
    max-width: 100%;
}

button.remove-row {
    padding: 3px 8px;
    font-size: 12px;
}



</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Edit Sales Request</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('sales_payment.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('sales_payment.submitEditRequest', $SalesPayment->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{$SalesPayment->order_no}}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $SalesPayment->date }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="sales_no" class="form-label">Sales No:</label>
                                <select name="sales_no" id="sales_no" class="form-control" required>
                                <option value="">Select Sales Order</option>
                                         @foreach ($SalesOrders as $SalesOrder)
                                                 <option value="{{ $SalesOrder->id }}" 
                                         {{ isset($SalesPayment) && $SalesOrder->id == $SalesPayment->sales_no ? 'selected' : '' }}>
                                            {{ $SalesOrder->order_no }}
                                     </option>

                                    @endforeach
                               </select>
                          </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="customer_id" class="form-label"> Customer:</label>
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    <option value="">Select Customers</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $customer->id == $SalesPayment->customer_id ? 'selected' : '' }}>
                                            {{ $customer->customer_name }}
                                        </option>                
                                        @endforeach
                                </select>
                            </div>
                          <!-- Shipping Mode -->
<div class="col-md-4">
    <label for="shipping_mode" class="form-label">Shipping Mode</label>
    <select class="form-control" id="shipping_mode" name="shipping_mode">
        <option value="Buy Air" {{ $SalesPayment->shipping_mode == 'Buy Air' ? 'selected' : '' }}>Buy Air</option>
        <option value="By Sea" {{ $SalesPayment->shipping_mode == 'By Sea' ? 'selected' : '' }}>By Sea</option>
        <option value="By Road" {{ $SalesPayment->shipping_mode == 'By Road' ? 'selected' : '' }}>By Road</option>
    </select>
</div>

<!-- Shipping Agent -->
<div class="col-md-4">
    <label for="shipping_agent" class="form-label">Shipping Agent</label>
    <select class="form-control" id="shipping_agent" name="shipping_agent">
        <option value="Qatar Airways" {{ $SalesPayment->shipping_agent == 'Qatar Airways' ? 'selected' : '' }}>Qatar Airways</option>
        <option value="Ethiopian Airlines" {{ $SalesPayment->shipping_agent == 'Ethiopian Airlines' ? 'selected' : '' }}>Ethiopian Airlines</option>
    </select>
</div>
<div class="col-md-4">
 <label for="shrinkage" class="form-label">Shrinkage:</label>
<input type="text" class="form-control" id="shrinkage" name="shrinkage" value="{{ $SalesPayment->shrinkage }}" >
 </div>
 <div class="col-md-4">
                                <label for="packaging" class="form-label">Packaging:</label>
                                <input type="text" class="form-control" id="packaging" name="packaging"  value="{{ $SalesPayment->packaging }}" >
                            </div>

                        </div><br>
                        <div class="table-responsive">
                        <table class="table table-bordered" id="productTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($SalesPayment->details as $key => $detail)
                                <tr>
                                    <td>
                                        <select name="products[{{ $key }}][product_id]" class="form-control product-select" required >
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-rate="{{ $product->rate }}" {{ $product->id == $detail->product_id ? 'selected' : '' }}>
                                                    {{ $product->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="products[{{ $key }}][qty]" class="form-control qty" value="{{ $detail->qty }}" step="0.01" required  ></td>
                                    <td><input type="text" name="products[{{ $key }}][rate]" class="form-control rate" value="{{ $detail->rate }}"  step="any"></td>
                                    <td><input type="text" name="products[{{ $key }}][total]" class="form-control total" value="{{ $detail->qty * $detail->rate }}" readonly  step="any"></td>
                                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
</div>
                        <button type="button" id="addRow" class="btn btn-success">Add Row</button>


                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label for="grand_total" class="form-label">Grand Total:</label>
                                <input type="number" id="total" name="grand_total" class="form-control" value="{{ $SalesPayment->grand_total }}" readonly step="any">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="advance_amount" class="form-label">Advance:</label>
                                <input type="number" id="advance_amount" name="advance_amount" class="form-control" value="{{ $SalesPayment->advance_amount }}" step="any">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="balance_amount" class="form-label">Balance:</label>
                                <input type="number" id="balance_amount" name="balance_amount" class="form-control" value="{{ $SalesPayment->balance_amount }}" readonly step="any"> 
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')


<script>
document.addEventListener('DOMContentLoaded', function () {
    const productTable = document.querySelector('#productTable tbody');
    const addRowBtn = document.getElementById('addRow');
    const advanceAmountField = document.getElementById('advance_amount');
    const shrinkageField = document.getElementById('shrinkage'); // Shrinkage field

    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('#productTable tbody tr').forEach(row => {
            const qtyInput = row.querySelector('.qty');
            const rateInput = row.querySelector('.rate');
            const totalInput = row.querySelector('.total');

            const qty = parseFloat(qtyInput.value) || 0;
            const rate = parseFloat(rateInput.value) || 0;

            if (!isNaN(qty) && !isNaN(rate)) {
                const total = qty * rate;
                totalInput.value = total.toFixed(2);
                grandTotal += total;
            }
        });

        // Apply shrinkage percentage if entered
        const shrinkage = parseFloat(shrinkageField.value) || 0;
        if (shrinkage > 0) {
            const shrinkageAmount = (shrinkage / 100) * grandTotal;
            grandTotal -= shrinkageAmount;
        }

        document.getElementById('total').value = grandTotal.toFixed(2);
        
        // Calculate balance amount after advance payment
        const advanceAmount = parseFloat(advanceAmountField.value) || 0;
        document.getElementById('balance_amount').value = (grandTotal - advanceAmount).toFixed(2);
    }

    // Event listeners for quantity, rate, advance, and shrinkage changes
    productTable.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty') || e.target.classList.contains('rate')) {
            calculateTotals();
        }
    });

    advanceAmountField.addEventListener('input', calculateTotals);
    shrinkageField.addEventListener('input', calculateTotals); // Trigger calculation on shrinkage change

    // Add new row on button click
    addRowBtn.addEventListener('click', function () {
        const index = productTable.children.length;
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="products[${index}][product_id]" class="form-control product-select" required >
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="products[${index}][qty]" class="form-control qty" value="1" required ></td>
            <td><input type="text" name="products[${index}][rate]" class="form-control rate"></td>
            <td><input type="text" name="products[${index}][total]" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        `;
        productTable.appendChild(newRow);
    });

    // Remove row event listener
    productTable.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculateTotals();
        }
    });
});

</script>

@endsection
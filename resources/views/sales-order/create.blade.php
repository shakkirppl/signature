@extends('layouts.layout')
@section('content')
<style>
.table {
    border-collapse: collapse;
    width: 80%;
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
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Sales Order</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('goodsout-order-create') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div><br />
                    @endif
                    <form action="{{ route('goodsout-order.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{ $invoice_no }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-4">
                                <label for="customer_id" class="form-label">Customer:</label>
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Rate(USD)</th>
                                    <th>Total(USD)</th>
                                    <th>Action</th> 
                                </tr>
                            </thead>
                            <tbody id="product-rows">
                                <tr>
                                    <td>
                                        <select name="products[0][product_id]" class="form-control product-select" required style="width: 200px;">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="products[0][qty]" class="form-control qty"  step="0.01" required style="width: 200px;"></td>
                                    <td><input type="text" name="products[0][rate]" class="form-control rate" step="any" style="width: 200px;"></td>
                                    <td><input type="text" name="products[0][total]" class="form-control total" readonly style="width: 200px;" step="any"></td>
                                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
</div>
                        <button type="button" class="btn btn-primary" id="add-row">Add New Row</button>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label for="grand_total" class="form-label">Grand Total:(USD)</label>
                                <input type="number" id="total" name="grand_total" class="form-control" step="any" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="advance_amount" class="form-label">Advance:(USD)</label>
                                <input type="number" id="advance_amount" name="advance_amount" class="form-control" step="any">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="balance_amount" class="form-label">Balance:(USD)</label>
                                <input type="number" id="balance_amount" name="balance_amount" class="form-control" readonly step="any">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productRows = document.getElementById('product-rows');
    const addRowBtn = document.getElementById('add-row');
    const grandTotalField = document.getElementById('total');
    const advanceAmountField = document.getElementById('advance_amount');
    const balanceAmountField = document.getElementById('balance_amount');

    // Add new row functionality
    addRowBtn.addEventListener('click', function () {
        const rowCount = productRows.children.length;
        const newRow = `
        <tr>
            <td>
                <select name="products[${rowCount}][product_id]" class="form-control product-select" required style="width: 200px;">
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="products[${rowCount}][qty]" class="form-control qty" value="1" min="1" required style="width: 200px;" ></td>
            <td><input type="text" name="products[${rowCount}][rate]" class="form-control rate" step="any" style="width: 200px;"></td>
            <td><input type="text" name="products[${rowCount}][total]" class="form-control total" readonly step="any" style="width: 200px;"></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        </tr>`;
        productRows.insertAdjacentHTML('beforeend', newRow);
    });

    // Handle changes in quantity, rate, and product selection
    productRows.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty') || e.target.classList.contains('rate') || e.target.classList.contains('product-select')) {
            const row = e.target.closest('tr');
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const rate = parseFloat(row.querySelector('.rate').value) || 0;

            // Update total field with decimals
            row.querySelector('.total').value = (qty * rate).toFixed(2);

            // Recalculate totals
            calculateTotals();
        }
    });

    // Remove a row
    productRows.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculateTotals();
        }
    });

    // Recalculate totals and update fields
    function calculateTotals() {
        let grandTotal = 0;

        // Calculate grand total from all rows
        document.querySelectorAll('.total').forEach(function (input) {
            grandTotal += parseFloat(input.value) || 0;
        });

        // Ensure values are displayed with two decimal places
        grandTotalField.value = grandTotal.toFixed(2);
        const advanceAmount = parseFloat(advanceAmountField.value) || 0;
        balanceAmountField.value = (grandTotal - advanceAmount).toFixed(2);
    }

    // Handle advance amount input
    advanceAmountField.addEventListener('input', function () {
        const grandTotal = parseFloat(grandTotalField.value) || 0;
        const advanceAmount = parseFloat(advanceAmountField.value) || 0;
        balanceAmountField.value = (grandTotal - advanceAmount).toFixed(2);
    });

    // Initial calculation to ensure totals are accurate
    calculateTotals();
});




</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
});
</script>


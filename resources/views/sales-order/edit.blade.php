@extends('layouts.layout')
@section('content')
<style>
/* Adjust spacing between table rows */
#componentTable tbody tr {
    line-height: 1.2em;
    margin-bottom: 0.3em;
}
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Edit Sales Order</h4>
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
                    <form action="{{ route('goodsout-order.update', $salesOrder->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{ $salesOrder->order_no }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $salesOrder->date }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="customer_id" class="form-label">Customer:</label>
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $salesOrder->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="product-rows">
                                @foreach ($salesOrder->details as $index => $detail)
                                    <tr>
                                        <td>
                                            <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-rate="{{ $product->rate }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="products[{{ $index }}][qty]" class="form-control qty" value="{{ $detail->qty }}" min="1" required></td>
                                        <td><input type="number" name="products[{{ $index }}][rate]" class="form-control rate" value="{{ $detail->rate }}"></td>
                                        <td><input type="number" name="products[{{ $index }}][total]" class="form-control total" value="{{ $detail->total }}" readonly></td>
                                        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary" id="add-row">Add New Row</button>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label for="grand_total" class="form-label">Grand Total:</label>
                                <input type="number" id="total" name="grand_total" class="form-control" value="{{ $salesOrder->grand_total }}" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="advance_amount" class="form-label">Advance:</label>
                                <input type="number" id="advance_amount" name="advance_amount" class="form-control" value="{{ $salesOrder->advance_amount }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="balance_amount" class="form-label">Balance:</label>
                                <input type="number" id="balance_amount" name="balance_amount" class="form-control" value="{{ $salesOrder->balance_amount }}" readonly>
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

<script>
// Similar JavaScript functionality as the Create Page
// Preloaded product rates and totals will automatically calculate and update balance
document.addEventListener('DOMContentLoaded', function () {
    const productRows = document.getElementById('product-rows');
    const addRowBtn = document.getElementById('add-row');
    const grandTotalField = document.getElementById('total'); 
    const advanceAmountField = document.getElementById('advance_amount'); 
    const balanceAmountField = document.getElementById('balance_amount'); 

    addRowBtn.addEventListener('click', function () {
        const rowCount = productRows.children.length;
        const newRow = `
        <tr>
            <td>
                <select name="products[${rowCount}][product_id]" class="form-control product-select" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="products[${rowCount}][qty]" class="form-control qty" value="1" min="1" required></td>
            <td><input type="number" name="products[${rowCount}][rate]" class="form-control rate"></td>
            <td><input type="number" name="products[${rowCount}][total]" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        </tr>`;
        productRows.insertAdjacentHTML('beforeend', newRow);
    });

    productRows.addEventListener('input', function (e) {
        const row = e.target.closest('tr');
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value || row.querySelector('.product-select').selectedOptions[0]?.dataset.rate) || 0;
        row.querySelector('.rate').value = rate;
        row.querySelector('.total').value = qty * rate;
        calculateTotals();
    });

    productRows.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculateTotals();
        }
    });

    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.total').forEach(input => {
            grandTotal += parseFloat(input.value) || 0;
        });
        grandTotalField.value = grandTotal.toFixed(2);
        const advanceAmount = parseFloat(advanceAmountField.value) || 0;
        balanceAmountField.value = (grandTotal - advanceAmount).toFixed(2);
    }

    advanceAmountField.addEventListener('input', () => {
        const grandTotal = parseFloat(grandTotalField.value) || 0;
        const advanceAmount = parseFloat(advanceAmountField.value) || 0;
        balanceAmountField.value = (grandTotal - advanceAmount).toFixed(2);
    });

    calculateTotals();
});
</script>

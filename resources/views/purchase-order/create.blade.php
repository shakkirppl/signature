@extends('layouts.layout')
@section('content')
<style>
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
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Purchase Order</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('purchase-order-create') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                    <form action="{{ route('purchase-order.store') }}" method="POST">
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
                                <label for="supplier_id" class="form-label">Suppliers:</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">Select Suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Total</th>
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
                <td>
                    <select name="products[0][type]" class="form-control" style="width: 150px;">
                        <option value="">Select Type</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </td>
                <td><input type="number" name="products[0][qty]" class="form-control qty" value="1" min="1" required style="width: 80px;"></td>
                <td><input type="number" name="products[0][rate]" class="form-control rate" style="width: 150px;"></td>
                <td><input type="number" name="products[0][total]" class="form-control total" readonly style="width: 150px;"></td>
                <td><button type="button" class="btn btn-danger remove-row" >Remove</button></td>
            </tr>
        </tbody>
    </table>
</div>

                        <button type="button" class="btn btn-primary" id="add-row" >Add New Row</button>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label for="grand_total" class="form-label">Grand Total:</label>
                                <input type="number" id="total" name="grand_total" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="advance_amount" class="form-label">Advance:</label>
                                <input type="number" id="advance_amount" name="advance_amount" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="balance_amount" class="form-label">Balance:</label>
                                <input type="number" id="balance_amount" name="balance_amount" class="form-control" readonly>
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
    const addRowBtn = document.getElementById('add-row');
    const productRows = document.getElementById('product-rows');
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
            <td>
                <select name="products[${rowCount}][type]" class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td><input type="number" name="products[${rowCount}][qty]" class="form-control qty" value="1" min="1" required></td>
            <td><input type="number" name="products[${rowCount}][rate]" class="form-control rate"></td>
            <td><input type="number" name="products[${rowCount}][total]" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        </tr>`;
        productRows.insertAdjacentHTML('beforeend', newRow);
    });

    // Handle changes in quantity, rate, and product selection
    productRows.addEventListener('input', function (e) {
        const row = e.target.closest('tr');

        if (e.target.classList.contains('product-select')) {
            const selectedProduct = e.target.selectedOptions[0];
            const rateField = row.querySelector('.rate');
            rateField.value = selectedProduct.dataset.rate || 0;
        }

        if (e.target.classList.contains('qty') || e.target.classList.contains('rate')) {
            updateRowTotal(row);
        }
    });

    // Remove a row
    productRows.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculateTotals();
        }
    });

    // Function to update row total
    function updateRowTotal(row) {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value) || 0;
        row.querySelector('.total').value = (qty * rate).toFixed(2);
        calculateTotals();
    }

    // Function to calculate totals
    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.total').forEach(function (input) {
            grandTotal += parseFloat(input.value) || 0;
        });

        grandTotalField.value = grandTotal.toFixed(2);
        updateBalance();
    }

    // Function to update balance amount
    function updateBalance() {
        const grandTotal = parseFloat(grandTotalField.value) || 0;
        const advanceAmount = parseFloat(advanceAmountField.value) || 0;
        balanceAmountField.value = (grandTotal - advanceAmount).toFixed(2);
    }

    // Handle advance amount input
    advanceAmountField.addEventListener('input', updateBalance);
});
</script>

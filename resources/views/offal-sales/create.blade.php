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
                        <div class="col-6">
                            <h4 class="card-title">Offal Sales</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('offal-sales-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('offal-sales.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" name="order_no" value="{{ $invoice_no }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" name="date" required id="date">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="lo_customer_id" class="form-label">Customer:</label>
                                <select name="lo_customer_id" class="form-control" required>
                                    <option value="">Select Customers</option>
                                    @foreach ($localcustomers as $localcustomer)
                                        <option value="{{ $localcustomer->id }}">{{ $localcustomer->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select name="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                    @endforeach
                                </select>
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
                                <tr>
                                    <td>
                                        <select name="products[0][product_id]" class="form-control product-select" required style="width: 200px;">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="products[0][qty]" class="form-control qty" step="0.01" required style="width: 150px;"></td>
                                    <td><input type="text" name="products[0][rate]" class="form-control rate"  step="0.01"style="width: 150px;"></td>
                                    <td><input type="text" name="products[0][total]" class="form-control total" readonly style="width: 150px;"></td>
                                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
</div>
                        <button type="button" class="btn btn-success mt-2" id="addRow">Add Row</button>

                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label for="grand_total" class="form-label">Grand Total:</label>
                                <input type="number" id="total" name="grand_total" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="advance_amount" class="form-label">Advance:</label>
                                <input type="number" id="advance_amount" name="advance_amount" class="form-control" step="0.01">
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
    const productTable = document.getElementById('productTable').querySelector('tbody');
    const addRowBtn = document.getElementById('addRow');
    const grandTotalField = document.getElementById('total');
    const advanceAmountField = document.getElementById('advance_amount');
    const balanceAmountField = document.getElementById('balance_amount');

    function calculateTotals() {
        let grandTotal = 0;
        productTable.querySelectorAll('tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const rate = parseFloat(row.querySelector('.rate').value) || 0;
            const total = qty * rate;
            row.querySelector('.total').value = total.toFixed(2);
            grandTotal += total;
        });
        grandTotalField.value = grandTotal.toFixed(2);
        const advanceAmount = parseFloat(advanceAmountField.value) || 0;
        balanceAmountField.value = (grandTotal - advanceAmount).toFixed(2);
    }

    addRowBtn.addEventListener('click', function () {
        const index = productTable.children.length;
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="products[${index}][product_id]" class="form-control product-select" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="products[${index}][qty]" class="form-control qty" value="1" min="1" required></td>
            <td><input type="text" name="products[${index}][rate]" class="form-control rate"></td>
            <td><input type="text" name="products[${index}][total]" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        `;
        productTable.appendChild(newRow);
    });

    productTable.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculateTotals();
        }
    });

    productTable.addEventListener('input', calculateTotals);
    advanceAmountField.addEventListener('input', calculateTotals);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
});
</script>

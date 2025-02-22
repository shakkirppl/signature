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
                            <h4 class="card-title"> Sales</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('sales_payment-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                    <form action="{{ route('sales_payment.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{ $invoice_no }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="customer_id" class="form-label"> Customer:</label>
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    <option value="">Select Customers</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                       
                        <div class="col-md-4">
    <label for="sales_no" class="form-label">Sales No:</label>
    <select name="sales_no" id="sales_no" class="form-control" required>
        <option value="">Select sales no</option>
        @foreach ($SalesOrders as $SalesOrder)
            <option value="{{ $SalesOrder->id }}">{{ $SalesOrder->order_no }}</option>
        @endforeach
    </select>
</div>
</div><br>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="products[0][product_id]" class="form-control product-select" required>
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="products[0][qty]" class="form-control qty" value="1" min="1" required></td>
                                    <td><input type="number" name="products[0][rate]" class="form-control rate" ></td>
                                    <td><input type="number" name="products[0][total]" class="form-control total" readonly></td>
                                </tr>
                            </tbody>
                        </table>
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
    const grandTotalField = document.getElementById('total');
    const advanceAmountField = document.getElementById('advance_amount');
    const balanceAmountField = document.getElementById('balance_amount');
    const qtyField = document.querySelector('.qty');
    const rateField = document.querySelector('.rate');
    const totalField = document.querySelector('.total');
    const productSelect = document.querySelector('.product-select');

    function calculateTotals() {
        const qty = parseFloat(qtyField.value) || 0;
        const rate = parseFloat(rateField.value || productSelect.selectedOptions[0].dataset.rate) || 0;
        rateField.value = rate;
        const total = qty * rate;
        totalField.value = total.toFixed(2);
        grandTotalField.value = total.toFixed(2);
        const advanceAmount = parseFloat(advanceAmountField.value) || 0;
        balanceAmountField.value = (total - advanceAmount).toFixed(2);
    }

    [qtyField, rateField, productSelect, advanceAmountField].forEach(element => {
        element.addEventListener('input', calculateTotals);
    });

    calculateTotals();
});
</script>
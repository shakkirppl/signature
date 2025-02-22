@extends('layouts.layout')
@section('content')
<style>
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
                        <div class="col-6">
                            <h4 class="card-title">Edit Offal Sale</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('offal-sales.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                    <form action="{{ route('offal-sales.update', $offalSale->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="order_no" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="order_no" name="order_no" value="{{ $offalSale->order_no }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $offalSale->date }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="lo_customer_id" class="form-label">Customer:</label>
                                <select name="lo_customer_id" id="lo_customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($localcustomers as $customer)
                                        <option value="{{ $customer->id }}" {{ $customer->id == $offalSale->lo_customer_id ? 'selected' : '' }}>
                                            {{ $customer->customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select name="shipment_id" id="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}" {{ $shipment->id == $offalSale->shipment_id ? 'selected' : '' }}>
                                            {{ $shipment->shipment_no }}
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($offalSale->details as $key => $detail)
                                <tr>
                                    <td>
                                        <select name="products[{{ $key }}][product_id]" class="form-control product-select" required>
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-rate="{{ $product->rate }}" {{ $product->id == $detail->product_id ? 'selected' : '' }}>
                                                    {{ $product->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="products[{{ $key }}][qty]" class="form-control qty" value="{{ $detail->qty }}" min="1" required></td>
                                    <td><input type="number" name="products[{{ $key }}][rate]" class="form-control rate" value="{{ $detail->rate }}"></td>
                                    <td><input type="number" name="products[{{ $key }}][total]" class="form-control total" value="{{ $detail->qty * $detail->rate }}" readonly></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <label for="grand_total" class="form-label">Grand Total:</label>
                                <input type="number" id="total" name="grand_total" class="form-control" value="{{ $offalSale->grand_total }}" readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="advance_amount" class="form-label">Advance:</label>
                                <input type="number" id="advance_amount" name="advance_amount" class="form-control" value="{{ $offalSale->advance_amount }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="balance_amount" class="form-label">Balance:</label>
                                <input type="number" id="balance_amount" name="balance_amount" class="form-control" value="{{ $offalSale->balance_amount }}" readonly>
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
document.addEventListener("DOMContentLoaded", function () {
    function updateTotal(row) {
        let qty = row.querySelector(".qty").value;
        let rate = row.querySelector(".rate").value;
        let totalField = row.querySelector(".total");

        let total = (qty * rate).toFixed(2);
        totalField.value = total;

        updateGrandTotal();
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll(".total").forEach((totalField) => {
            grandTotal += parseFloat(totalField.value) || 0;
        });
        document.getElementById("total").value = grandTotal.toFixed(2);
        updateBalance();
    }

    function updateBalance() {
        let grandTotal = parseFloat(document.getElementById("total").value) || 0;
        let advance = parseFloat(document.getElementById("advance_amount").value) || 0;
        let balance = grandTotal - advance;
        document.getElementById("balance_amount").value = balance.toFixed(2);
    }

    // Event listeners for quantity and rate fields
    document.querySelectorAll(".qty, .rate").forEach((input) => {
        input.addEventListener("input", function () {
            updateTotal(this.closest("tr"));
        });
    });

    // Event listener for advance amount
    document.getElementById("advance_amount").addEventListener("input", updateBalance);
});
</script>

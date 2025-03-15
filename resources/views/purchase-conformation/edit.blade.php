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
                    <h4 class="card-title">Edit Purchase Confirmation</h4>
                    <form action="{{ route('purchase-conformation.update', $purchaseConfirmation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="weight_id" value="{{ $purchaseConfirmation->weight_id }}">
                        <input type="hidden" name="inspection_id" value="{{ $purchaseConfirmation->inspection_id }}">
                        <input type="hidden" name="purchaseOrder_id" value="{{ $purchaseConfirmation->purchaseOrder_id }}">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="weight_code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" name="weight_code" value="{{ $purchaseConfirmation->weight_code }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="invoice_number" class="form-label">Confirmation Code:</label>
                                <input type="text" class="form-control" name="invoice_number" value="{{ $purchaseConfirmation->invoice_number }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select id="shipment_id" class="form-control" disabled>
                                    <option value="{{ $purchaseConfirmation->shipment->id ?? '' }}">{{ $purchaseConfirmation->shipment->shipment_no ?? 'No Shipment Assigned' }}</option>
                                </select>
                                <input type="hidden" name="shipment_id" value="{{ $purchaseConfirmation->shipment->id ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label">Supplier:</label>
                                <select name="supplier_id" class="form-control" required>
                                    <option value="{{ $purchaseConfirmation->supplier->id }}" selected>{{ $purchaseConfirmation->supplier->name }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" name="date" value="{{ $purchaseConfirmation->date }}" required>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Total Weight</th>
                                        <th>Price/Kg</th>
                                        <th>Transportation/Kg</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="product-details">
                                    @foreach ($purchaseConfirmation->details as $index => $detail)
                                        <tr>
                                            <td>
                                                <select name="products[{{ $index }}][product_id]" class="form-control" required style="width: 100px;">
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="products[{{ $index }}][total_accepted_qty]" class="form-control" value="{{ $detail->total_accepted_qty }}" min="1" style="width: 100px;"></td>
                                            <td><input type="text" name="products[{{ $index }}][total_weight]" class="form-control weight" value="{{ $detail->total_weight }}" step="any" style="width: 100px;"></td>
                                            <td><input type="text" name="products[{{ $index }}][rate]" class="form-control rate" value="{{ $detail->rate }}" step="any" style="width: 100px;"></td>
                                            <td><input type="text" name="products[{{ $index }}][transportation_amount]" class="form-control transport" value="{{ $detail->transportation_amount }}" step="any" style="width: 100px;"></td>
                                            <td><input type="text" name="products[{{ $index }}][total]" class="form-control" value="{{ $detail->total }}" step="any" readonly style="width: 100px;"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="item_total" class="form-label">Item Amount:</label>
                                <input type="text" name="item_total" class="form-control" value="{{ $purchaseConfirmation->item_total }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="total_expense" class="form-label">Additional Expense:</label>
                                <input type="text" name="total_expense" class="form-control" value="{{ $purchaseConfirmation->total_expense }}">
                            </div>
                            <div class="col-md-4">
                                <label for="grand_total" class="form-label">Grand Total:</label>
                                <input type="text" name="grand_total" class="form-control" value="{{ $purchaseConfirmation->grand_total }}" readonly>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="advance_amount" class="form-label">Advanced Amount:</label>
                                <input type="text" name="advance_amount" class="form-control" value="{{ $purchaseConfirmation->advance_amount }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="balance_amount" class="form-label">Balance Amount:</label>
                                <input type="text" name="balance_amount" class="form-control" value="{{ $purchaseConfirmation->balance_amount }}" readonly>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Event listener for all table inputs to update totals dynamically
    document.getElementById("product-details").addEventListener("input", function (event) {
        const target = event.target;
        if (target.classList.contains("weight") || target.classList.contains("rate") || target.classList.contains("transport")) {
            updateRowTotal(target.closest("tr"));
            calculateTotals();
        }
    });

    // Event listener for additional expense input
    document.getElementById("total_expense").addEventListener("input", function () {
        calculateTotals(false);
    });

    // Format total_expense when input loses focus
    document.getElementById("total_expense").addEventListener("blur", function () {
        let additionalExpense = parseFloat(this.value.replace(/,/g, "")) || 0;
        this.value = additionalExpense.toFixed(2);
        calculateTotals();
    });

    function updateRowTotal(row) {
        const totalWeight = parseFloat(row.querySelector(".weight").value.replace(/,/g, "")) || 0;
        const rate = parseFloat(row.querySelector(".rate").value.replace(/,/g, "")) || 0;
        const transportationAmount = parseFloat(row.querySelector(".transport").value.replace(/,/g, "")) || 0;

        // Formula: total = (total_weight × rate) + (total_weight × transportation_amount)
        const rowTotal = ((totalWeight * rate) + (totalWeight * transportationAmount)).toFixed(2);

        row.querySelector(".total").value = new Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(rowTotal);
    }

    function calculateTotals(formatExpense = true) {
        let itemTotal = 0;

        // Calculate total of all item rows
        document.querySelectorAll("#product-details tr").forEach(row => {
            updateRowTotal(row);
            let rowTotal = parseFloat(row.querySelector(".total").value.replace(/,/g, "")) || 0;
            itemTotal += rowTotal;
        });

        // Format and display item total
        document.getElementById("item_total").value = new Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(itemTotal);

        let additionalExpense = parseFloat(document.getElementById("total_expense").value.replace(/,/g, "")) || 0;
        if (formatExpense) {
            document.getElementById("total_expense").value = new Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(additionalExpense);
        }

        let grandTotalAmount = itemTotal + additionalExpense;
        document.getElementById("grand_total").value = new Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(grandTotalAmount);

        const advanceAmount = parseFloat(document.getElementById("advance_amount").value.replace(/,/g, "")) || 0;
        let balanceAmount = grandTotalAmount - advanceAmount;

        // Format and display balance amount
        document.getElementById("balance_amount").value = new Intl.NumberFormat("en-US", { minimumFractionDigits: 2 }).format(balanceAmount);
    }
});
</script>
@endsection

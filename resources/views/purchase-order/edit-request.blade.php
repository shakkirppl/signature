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
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Edit Purchase Order</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('purchase-orders') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('purchaseorder.submitEditRequest', $purchaseOrder->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{ $purchaseOrder->order_no }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $purchaseOrder->date }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label">Suppliers:</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $supplier->id == $purchaseOrder->supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
    <label for="shipment_id" class="form-label">Shipment No:</label>
    <select name="shipment_id" id="shipment_id" class="form-control" required>
        <option value="">Select Shipment No</option>
        @foreach ($shipments as $shipment)
            <option value="{{ $shipment->id }}" 
                {{ $shipment->id == $purchaseOrder->shipment_id ? 'selected' : '' }}>
                {{ $shipment->shipment_no }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label for="SalesOrder_id" class="form-label">Sales Order No:</label>
    <select name="SalesOrder_id" id="SalesOrder_id" class="form-control" required>
        <option value="">Select Sales No</option>
        @foreach ($SalesOrders as $SalesOrder)
            <option value="{{ $SalesOrder->id }}" 
                {{ $SalesOrder->id == $purchaseOrder->SalesOrder_id ? 'selected' : '' }}>
                {{ $SalesOrder->order_no }}
            </option>
        @endforeach
    </select>
</div>

                        </div>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <!-- <th>Type</th> -->
                                   
                                    <th>Qty</th>
                                    <th>Male</th>
                                    <th>Female</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="product-rows">
                            @if (!empty($purchaseOrder->products) && count($purchaseOrder->products) > 0)
    @foreach ($purchaseOrder->products as $index => $orderProduct)
        <tr>
            <td>
                <select name="products[{{ $index }}][product_id]" class="form-control product-select" required >
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-rate="{{ $product->rate }}" 
                            {{ $product->id == $orderProduct->product_id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <!-- <td>
                <select name="products[{{ $index }}][type]" class="form-control" required style="width: 150px;">
                    <option value="Male" {{ $orderProduct->type == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $orderProduct->type == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </td> -->
            <td><input type="text" name="products[{{ $index }}][qty]" class="form-control qty" value="{{ $orderProduct->qty }}" min="1" required ></td>
            <td><input type="text" name="products[{{ $index }}][male]" class="form-control male" value="{{ $orderProduct->male }}" min="1" required ></td>
            <td><input type="text" name="products[{{ $index }}][female]" class="form-control female" value="{{ $orderProduct->female }}" min="1" required ></td>
            <!-- <td><input type="number" name="products[{{ $index }}][rate]" class="form-control rate" value="{{ $orderProduct->rate }}" style="width: 150px;"></td>
            <td><input type="number" name="products[{{ $index }}][total]" class="form-control total" value="{{ $orderProduct->total }}" readonly style="width: 150px;"></td> -->
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="text-center">No products added yet.</td>
    </tr>
@endif

                            </tbody>
                        </table>
</div>

                        <button type="button" class="btn btn-primary" id="add-row">Add New Row</button>

                        <!-- <div class="row mt-4">
                            <div class="col-md-3">
                                <label for="grand_total" class="form-label">Grand Total:</label>
                                <input type="number" id="total" name="grand_total" class="form-control" value="{{ $purchaseOrder->grand_total }}" readonly>
                            </div>
                        </div> -->
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="advance_amount" class="form-label">Advance:</label>
                                <input type="number" id="advance_amount" name="advance_amount" class="form-control" value="{{ $purchaseOrder->advance_amount }}">
                            </div>
                        </div>
                        <!-- <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="balance_amount" class="form-label">Balance:</label>
                                <input type="number" id="balance_amount" name="balance_amount" class="form-control" value="{{ $purchaseOrder->balance_amount }}" readonly>
                            </div>
                        </div> -->

                        <button type="submit" class="btn btn-primary mt-4">Update</button>
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

    // Function to update male and female fields based on quantity
    function updateGenderDistribution(row) {
        const qtyField = row.querySelector('.qty');
        const maleField = row.querySelector('.male');
        const femaleField = row.querySelector('.female');

        let qty = parseInt(qtyField.value) || 0;
        maleField.value = Math.floor(qty * 0.75); // 75% of quantity
        femaleField.value = qty - maleField.value; // Remaining 25%
    }

    // Event listener for quantity input
    productRows.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty')) {
            updateGenderDistribution(e.target.closest('tr'));
        }
    });

    // Add new row functionality
    addRowBtn.addEventListener('click', function () {
        const rowCount = productRows.children.length;
        const newRow = `
            <tr>
                <td>
                    <select name="products[${rowCount}][product_id]" class="form-control product-select" required >
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="products[${rowCount}][qty]" class="form-control qty" value="0" min="1" required ></td>
                <td><input type="text" name="products[${rowCount}][male]" class="form-control male" value="0" required  readonly></td>
                <td><input type="text" name="products[${rowCount}][female]" class="form-control female" value="0" required  readonly></td>
                <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
            </tr>`;

        productRows.insertAdjacentHTML('beforeend', newRow);
    });

    // Remove row event listener
    productRows.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });
});
// document.addEventListener('DOMContentLoaded', function () {
//     // Fetching existing elements for the calculation
//     const productRows = document.getElementById('product-rows');
//     const grandTotalField = document.getElementById('total');
//     const advanceAmountField = document.getElementById('advance_amount');
//     const balanceAmountField = document.getElementById('balance_amount');
    
//     // Add Row functionality
//     document.getElementById('add-row').addEventListener('click', function () {
//         const rowCount = productRows.children.length;
//         const newRow = `
//         <tr>
//             <td>
//                 <select name="products[${rowCount}][product_id]" class="form-control product-select" required>
//                     <option value="">Select Product</option>
//                     @foreach ($products as $product)
//                         <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
//                     @endforeach
//                 </select>
//             </td>
//             <td>
//                 <select name="products[${rowCount}][type]" class="form-control" required>
//                     <option value="">Select Type</option>
//                     <option value="Male">Male</option>
//                     <option value="Female">Female</option>
//                 </select>
//             </td>
//             <td><input type="number" name="products[${rowCount}][qty]" class="form-control qty" value="1" min="1" required></td>
//             <td><input type="number" name="products[${rowCount}][rate]" class="form-control rate"></td>
//             <td><input type="number" name="products[${rowCount}][total]" class="form-control total" readonly></td>
//             <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
//         </tr>`;
//         productRows.insertAdjacentHTML('beforeend', newRow);
//     });

//     // Handle changes in quantity, rate, and product selection
//     productRows.addEventListener('input', function (e) {
//         if (
//             e.target.classList.contains('qty') ||
//             e.target.classList.contains('rate') ||
//             e.target.classList.contains('product-select')
//         ) {
//             const row = e.target.closest('tr');
//             const qty = parseFloat(row.querySelector('.qty').value) || 0;
//             const rate =
//                 parseFloat(
//                     row.querySelector('.rate').value ||
//                     row.querySelector('.product-select').selectedOptions[0].dataset.rate
//                 ) || 0;

//             // Update rate and total fields
//             row.querySelector('.rate').value = rate;
//             row.querySelector('.total').value = qty * rate;

//             // Recalculate totals
//             calculateTotals();
//         }
//     });

//     // Remove a row
//     productRows.addEventListener('click', function (e) {
//         if (e.target.classList.contains('remove-row')) {
//             e.target.closest('tr').remove();
//             calculateTotals();
//         }
//     });

//     // Recalculate totals and update fields
//     function calculateTotals() {
//         let grandTotal = 0;

//         // Calculate grand total from all rows
//         document.querySelectorAll('.total').forEach(function (input) {
//             grandTotal += parseFloat(input.value) || 0;
//         });

//         // Update grand total and balance
//         grandTotalField.value = grandTotal.toFixed(2);
//         const advanceAmount = parseFloat(advanceAmountField.value) || 0;
//         balanceAmountField.value = (grandTotal - advanceAmount).toFixed(2);
//     }

//     // Handle advance amount input
//     advanceAmountField.addEventListener('input', function () {
//         const grandTotal = parseFloat(grandTotalField.value) || 0;
//         const advanceAmount = parseFloat(advanceAmountField.value) || 0;
//         balanceAmountField.value = (grandTotal - advanceAmount).toFixed(2);
//     });

//     // Initial calculation to ensure totals are accurate when loading the page
//     calculateTotals();
// });
</script>


@extends('layouts.layout')

@section('content')
<style>
    /* Adjust spacing between table rows */
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
                            <h4 class="card-title">Inspection & Animal Receive Detail</h4>
                        </div>
                        <div class="col-6 text-end">
                            <!-- You can add any additional button or content here if needed -->
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
                    
                    <form id="temperatureForm" action="{{ route('inspection.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <!-- Inspection No -->
                            <div class="col-md-3">
                                <label for="inspection_no"><strong>Inspection No:</strong></label>
                                <input type="text" name="inspection_no" id="inspection_no" class="form-control" value="{{$invoice_no}}" readonly>
                            </div>

                            <!-- Order No -->
                            <div class="col-md-3">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{ $purchaseOrder->order_no }}" readonly>
                                <input type="hidden" class="form-control" id="purchaseOrder_id" name="purchaseOrder_id" value="{{ $purchaseOrder->id }}" readonly>
                            </div>

                            <!-- Date -->
                            <div class="col-md-3">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="" required>
                            </div>

                            <!-- Supplier Name -->
                            <div class="col-md-3">
                               <label for="supplier_name" class="form-label">Supplier:</label>
                                <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="{{ $purchaseOrder->supplier->name }}" readonly>
                               <input type="hidden" name="supplier_id" value="{{ $purchaseOrder->supplier_id }}">
                            </div>

                            <!-- Shipment No -->
                            <div class="col-md-3">
                                <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select name="shipment_id" id="shipment_id" class="form-control" required>
                                    <option value="">-- Select Shipment --</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}" {{ $purchaseOrder->shipment_id == $shipment->id ? 'selected' : '' }}>
                                            {{ $shipment->shipment_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Mark -->
                            <div class="col-md-3">
                                <label for="mark" class="form-label">Mark:</label>
                                <input type="text" class="form-control" id="mark" name="mark" value="" required>
                            </div>

                            <!-- Signature -->
                         <div class="col-md-6 mt-3">
        <label>Inspector Signature</label><br>
        <canvas id="signature-pad" width="300" height="100" style="border:1px solid #ccc;"></canvas><br>
        <button type="button" class="btn btn-sm btn-warning mt-2" onclick="clearSignature()">Clear</button>
        <input type="hidden" name="signature" id="inspector_signature">
    </div>
                        </div>

                        <!-- Product Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Actual Quantity</th>
                                        <th>Received Quantity</th>
                                        <th>Male</th>
                                        <th>Female</th>
                                        <th>Male Rejected Quantity</th>
                                        <th>Female Rejected Quantity</th>
                                        <th>Rejected Reasons</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="product-rows">
                                    @foreach ($purchaseOrder->details as $index => $detail)
                                        <tr>
                                            <!-- Product Selection -->
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

                                            <!-- Actual Quantity -->
                                            <td>
                                                <input type="text" name="products[{{ $index }}][qty]" class="form-control qty" value="{{ $detail->qty }}"  style="width: 150px;">
                                            </td>

                                            <!-- Received Quantity -->
                                            <td>
                                                <input type="text" name="products[{{ $index }}][received_qty]" class="form-control qty" value="{{ $detail->received_qty }}"  style="width: 150px;">
                                            </td>

                                            <!-- Male Accepted Quantity -->
                                            <td>
                                                <input type="text" name="products[{{ $index }}][male_accepted_qty]" class="form-control accepted-qty" value="{{ $detail->accepted_qty ?? '' }}" min="0" required style="width: 150px;">
                                            </td>

                                            <!-- Female Accepted Quantity -->
                                            <td>
                                                <input type="text" name="products[{{ $index }}][female_accepted_qty]" class="form-control accepted-qty" value="{{ $detail->accepted_qty ?? '' }}" min="0" required style="width: 150px;">
                                            </td>

                                            <!-- Male Rejected Quantity -->
                                            <td>
                                                <input type="text" name="products[{{ $index }}][male_rejected_qty]" class="form-control rejected-qty" value="{{ $detail->rejected_qty ?? '' }}" min="0" style="width: 150px;" >
                                            </td>

                                            <!-- Female Rejected Quantity -->
                                            <td>
                                                <input type="text" name="products[{{ $index }}][female_rejected_qty]" class="form-control rejected-qty" value="{{ $detail->rejected_qty ?? '' }}" min="0" style="width: 150px;" >
                                            </td>

                                            <!-- Rejected Reason -->
                                            <td>
                                                <select name="products[{{ $index }}][rejected_reason]" class="form-control rejected-reason" style="width: 150px;" >
                                                    <option value="">Select Reason</option>
                                                    @foreach ($rejectReasons as $reason)
                                                        <option value="{{ $reason->id }}" {{ isset($detail->rejected_reasons) && $detail->rejected_reasons == $reason->id ? 'selected' : '' }}>
                                                            {{ $reason->rejected_reasons }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <!-- Hidden Fields for Rate and Total -->
                                            <input type="hidden" name="products[{{ $index }}][rate]" value="{{ $detail->rate }}">
                                            <input type="hidden" name="products[{{ $index }}][total]" value="{{ $detail->total }}" readonly>

                                            <!-- Remove Row Button -->
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        function clearSignature() {
            signaturePad.clear();
        }

        document.querySelector('button[onclick="clearSignature()"]').addEventListener('click', clearSignature);

        document.getElementById('temperatureForm').addEventListener('submit', function (e) {
            if (signaturePad.isEmpty()) {
                alert('Inspector signature is required.');
                e.preventDefault();
            } else {
                const dataURL = signaturePad.toDataURL('image/png');
                document.getElementById('inspector_signature').value = dataURL;
            }
        });
    });
</script>







<script>
document.addEventListener('DOMContentLoaded', function () {


    productRows.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculateTotals();
        }
    });

   
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.qty').forEach(input => {
        input.addEventListener('input', function () {
            let row = this.closest('tr');
            let receivedQty = parseInt(row.querySelector('[name^="products"][name$="[received_qty]"]').value) || 0;

            // Calculate Male (75%) and Female (25%) distribution
            let maleQtyField = row.querySelector('[name^="products"][name$="[male]"]');
            let femaleQtyField = row.querySelector('[name^="products"][name$="[female]"]');

            if (maleQtyField && femaleQtyField) {
                maleQtyField.value = Math.floor(receivedQty * 0.75); // 75% of received quantity
                femaleQtyField.value = Math.ceil(receivedQty * 0.25); // 25% of received quantity
            }
        });
    });
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.accepted-qty').forEach(input => {
        input.addEventListener('input', function () {
            let row = this.closest('tr');
            let maleQty = parseInt(row.querySelector('.male')?.value) || 0;
            let femaleQty = parseInt(row.querySelector('.female')?.value) || 0;
            let maleAcceptedQty = parseInt(row.querySelector('[name^="products"][name$="[male_accepted_qty]"]')?.value) || 0;
            let femaleAcceptedQty = row.querySelector('[name^="products"][name$="[female_accepted_qty]"]')?.value;

            let maleRejectedQtyField = row.querySelector('[name^="products"][name$="[male_rejected_qty]"]');
            let femaleRejectedQtyField = row.querySelector('[name^="products"][name$="[female_rejected_qty]"]');

            // Update Male Rejected Quantity Immediately
            if (maleRejectedQtyField) {
                maleRejectedQtyField.value = Math.max(maleQty - maleAcceptedQty, 0);
            }

            // Update Female Rejected Quantity Only After User Enters Female Accepted Qty
            if (femaleRejectedQtyField) {
                if (femaleAcceptedQty !== "") { // Check if user entered a value
                    femaleRejectedQtyField.value = Math.max(femaleQty - parseInt(femaleAcceptedQty), 0);
                } else {
                    femaleRejectedQtyField.value = ""; // Keep it empty if no value entered
                }
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
});
</script>
<script>
    $(document).on('keydown', 'input, select', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission
            const inputs = $('#product-rows').find('input, select').filter(':visible');
            const index = inputs.index(this);

            if (index !== -1 && index + 1 < inputs.length) {
                inputs.eq(index + 1).focus();
            }
        }
    });
</script>






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

button.remove-row {
    padding: 5px 10px;
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
                    <h4 class="card-title">Purchase Confirmation</h4>
                    <form action="{{ route('purchase-conformation.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="weight_id" value="{{ $WeightCalculatorMaster->id }}">
                        <input type="hidden" name="inspection_id" value="{{ $WeightCalculatorMaster->inspection_id }}">
                        <input type="hidden" name="purchaseOrder_id" value="{{$WeightCalculatorMaster->purchaseOrder_id  }}">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="order_no" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="weight_code" name="weight_code" value="{{ $WeightCalculatorMaster->weight_code }}" readonly>
                                
                            </div>
                            <div class="col-md-4">
                                <label for="invoice_number" class="form-label">Conformation Code:</label>
                                <input type="invoice_number" class="form-control" id="invoice_number" name="invoice_number" value="{{ $invoice_no }}" readonly>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select id="shipment_id" class="form-control" disabled>
                                       <option value="{{ $WeightCalculatorMaster->shipment->id ?? '' }}" >
                                            {{ $WeightCalculatorMaster->shipment->shipment_no ?? 'No Shipment Assigned' }}
                                 </option>
                               </select>
                                <input type="hidden" name="shipment_id" value="{{ $WeightCalculatorMaster->shipment->id ?? '' }}">

                            </div>
                            </div>
                            <div class="row mb-3">
 
                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label">Supplier:</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="{{ $WeightCalculatorMaster->supplier->id }}" selected>{{ $WeightCalculatorMaster->supplier->name }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="" required>
                            </div>
                           
                        </div>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Mark</th>
                                    <th>Total Accepted Quandity</th>
                                    <th>Total Weight</th>
                                    <th>Price/Kg</th>
                                    <th>Transportation/Kg</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="product-details">
                                @foreach ($WeightCalculatorMaster->details as $index => $detail)
                                    <tr>
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
                                      <td>
                                        <input type="text" name="products[{{ $index }}][mark]" class="form-control qty" value="" min="1" style="width: 200px;">
                                        </td>
                                        <td>
                                        <input type="number" name="products[{{ $index }}][total_accepted_qty]" class="form-control qty" value="{{$detail->total_accepted_qty }}" min="1" style="width: 200px;">
                                        </td>
                                        <td>
                                        <input type="number" name="products[{{ $index }}][total_weight]" class="form-control weight" value="{{$detail->weight }}"  style="width: 200px;">
                                        </td>
                                        <td>
         
                                            <input type="number" name="products[{{ $index }}][rate]" class="form-control rate" value="0" style="width: 200px;">
                                        </td>
                                        <td>
         
                                         <input type="number" name="products[{{ $index }}][transportation_amount]" class="form-control rate" value="0" style="width: 200px;">
                                       </td>
                                        <td>
                                        <input type="number" name="products[{{ $index }}][total]" class="form-control total" value="0" readonly style="width: 200px;">

                                        </td>
                                        <td>
                                        <input type="hidden" name="products[{{ $index }}][original_total_accepted_qty]" value="{{ $detail->total_accepted_qty }}">
                                         <input type="hidden" name="products[{{ $index }}][original_total_weight]" value="{{ $detail->weight }}">

                                        </td>
                                       

                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
</div>
                         <br>
                        <div class="row">
   
<div class="col-md-5">
  

    </div>
    <div class="col-md-1"></div>

    <!-- Summary Section -->
    <div class="col-md-6">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="item_total" class="form-label">Item Amount:</label readonly>
            <input type="number" id="item_total" name="item_total" class="form-control" value="{{ $WeightCalculatorMaster->details->sum(fn($d) => $d->accepted_qty * $d->rate) }}" >
        </div>
        <div class="col-md-4">
            <label for="total_expense" class="form-label">Additional Expense:</label>
            <input type="number" id="total_expense" name="total_expense" class="form-control" >
        </div>
        <div class="col-md-4">
            <label for="grand_total" class="form-label">Grand Total:</label>
            <input type="number" id="grand_total" name="grand_total" class="form-control" readonly>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="advance_amount" class="form-label">Advanced Amount:</label>
            <input type="number" id="advance_amount" name="advance_amount" class="form-control"  value="{{ $order->advance_amount ?? 0 }}" readonly>       
         </div>
        <div class="col-md-6">
            <label for="balance_amount" class="form-label">Balance Amount:</label readonly>
            <input type="number" id="balance_amount" name="balance_amount" class="form-control" >
        </div>
    </div>
</div>


                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    calculateTotals();

    // Event listener for input changes in product details
    document.getElementById('product-details').addEventListener('input', function (event) {
        const target = event.target;
        if (target.classList.contains('qty') || target.classList.contains('rate') || target.classList.contains('weight')) {
            updateRowTotal(target.closest('tr'));
            calculateTotals();
        }
    });

    // Event listener for additional expense input
    document.getElementById('total_expense').addEventListener('input', function () {
        calculateTotals();
    });

    function updateRowTotal(row) {
        const totalWeight = parseFloat(row.querySelector('.weight').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value) || 0;
        const transportationAmount = parseFloat(row.querySelector('[name^="products"][name$="[transportation_amount]"]').value) || 0;

        const totalField = row.querySelector('.total');
        totalField.value = ((totalWeight * rate) + (transportationAmount * totalWeight)).toFixed(2);
    }

    function calculateTotals() {
        let itemTotal = 0;

        // Calculate item total
        document.querySelectorAll('#product-details tr').forEach(row => {
            updateRowTotal(row);
            itemTotal += parseFloat(row.querySelector('.total').value) || 0;
        });

        // Get additional expense value
        let additionalExpense = parseFloat(document.getElementById('total_expense').value) || 0;

        // Update fields
        document.getElementById('item_total').value = itemTotal.toFixed(2);
        document.getElementById('total_expense').value = additionalExpense.toFixed(2);

        // Calculate grand total
        let grandTotalAmount = itemTotal + additionalExpense;
        document.getElementById('grand_total').value = grandTotalAmount.toFixed(2);

        // Balance amount calculation
        const advanceAmount = parseFloat(document.getElementById('advance_amount').value) || 0;
        document.getElementById('balance_amount').value = (grandTotalAmount - advanceAmount).toFixed(2);
    }
});



    

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function initializeRows() {
        document.querySelectorAll('.product-select').forEach(select => {
            const selectedProduct = select.options[select.selectedIndex].text;
            const row = select.closest('tr');

            if (selectedProduct === "Live Goat") {
                addAdditionalRow(row);
            }
        });
    }

    document.getElementById('product-details').addEventListener('change', function (event) {
        if (event.target.classList.contains('product-select')) {
            const selectedProduct = event.target.options[event.target.selectedIndex].text;
            const row = event.target.closest('tr');

            if (selectedProduct === "Live Goat") {
                addAdditionalRow(row);
            } else {
                removeAdditionalRow(row);
            }
        }
    });

    function addAdditionalRow(originalRow) {
        const nextRow = originalRow.nextElementSibling;
        if (nextRow && nextRow.classList.contains('additional-live-goat')) {
            return; // Avoid duplicate rows
        }

        // Clone the row
        const newRow = originalRow.cloneNode(true);
        newRow.classList.add('additional-live-goat'); // Mark as additional row

        // Keep the product name the same
        let productSelect = newRow.querySelector('.product-select');
        if (productSelect) {
            for (let option of productSelect.options) {
                if (option.text === "Live Goat") {
                    option.selected = true;
                    break;
                }
            }
        }

        // Get original values before modification
        let originalQtyInput = originalRow.querySelector('[name*="[total_accepted_qty]"]');
        let originalWeightInput = originalRow.querySelector('[name*="[total_weight]"]');
        
        if (!originalQtyInput.hasAttribute('data-original')) {
            originalQtyInput.setAttribute('data-original', originalQtyInput.value);
        }
        if (!originalWeightInput.hasAttribute('data-original')) {
            originalWeightInput.setAttribute('data-original', originalWeightInput.value);
        }

        // Clear the new row's input values
        newRow.querySelector('[name*="[total_accepted_qty]"]').value = "";
        newRow.querySelector('[name*="[total_weight]"]').value = "";

        // Insert new row after original row
        originalRow.parentNode.insertBefore(newRow, originalRow.nextSibling);

        // Add event listeners to subtract values from the original row
        newRow.querySelector('[name*="[total_accepted_qty]"]').addEventListener('input', function () {
            updateOriginalRowQty(originalRow, newRow);
        });

        newRow.querySelector('[name*="[total_weight]"]').addEventListener('input', function () {
            updateOriginalRowWeight(originalRow, newRow);
        });
    }

    function updateOriginalRowQty(originalRow, newRow) {
        let originalQtyInput = originalRow.querySelector('[name*="[total_accepted_qty]"]');
        let newQtyInput = newRow.querySelector('[name*="[total_accepted_qty]"]');
        
        let newQty = parseFloat(newQtyInput.value) || 0;
        let originalQty = parseFloat(originalQtyInput.getAttribute('data-original')) || 0;

        if (newQty > originalQty) {
            alert("Error: Entered quantity exceeds available quantity!");
            newQtyInput.value = "";
            return;
        }

        // Correct subtraction logic
        originalQtyInput.value = originalQty - newQty;
    }

    function updateOriginalRowWeight(originalRow, newRow) {
        let originalWeightInput = originalRow.querySelector('[name*="[total_weight]"]');
        let newWeightInput = newRow.querySelector('[name*="[total_weight]"]');

        let newWeight = parseFloat(newWeightInput.value) || 0;
        let originalWeight = parseFloat(originalWeightInput.getAttribute('data-original')) || 0;

        if (newWeight > originalWeight) {
            alert("Error: Entered weight exceeds available weight!");
            newWeightInput.value = "";
            return;
        }

        // Correct subtraction logic for weight
        originalWeightInput.value = originalWeight - newWeight;
    }

    function removeAdditionalRow(originalRow) {
        const nextRow = originalRow.nextElementSibling;
        if (nextRow && nextRow.classList.contains('additional-live-goat')) {
            nextRow.remove();
        }
    }

    initializeRows();
});
</script>









@endsection

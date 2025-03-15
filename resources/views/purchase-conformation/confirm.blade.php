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

#shipment_id:disabled {
    color: black !important; 
    background-color: #e9ecef !important; 
    opacity: 1 !important; 
    cursor: not-allowed;
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
                                    <th style="width: 5%;">Product</th>
                                  
                                    <th style="width: 5%;">Quandity</th>
                                    <th style="width: 5%;" > Weight</th>
                                    <th style="width: 10%;">Price/Kg</th>
                                    <th style="width: 10%;">Transportation/Kg</th>
                                    <th style="width: 10%;">Total</th>
                                </tr>
                            </thead>
                            <tbody id="product-details">                                 
                                    @foreach ($WeightCalculatorMaster->details as $index => $detail)                                     
                                        <tr class="product-row">                                         
                                            <td>                                           
                                                <select name="products[{{ $index }}][product_id]" class="form-control product-select" required  style="width: 120px;">                                               
                                                    <option value="">Select Product</option>                                                   
                                                    @foreach ($products as $product)                                                     
                                                        <option value="{{ $product->id }}" data-rate="{{ $product->rate }}" 
                                                            {{ $detail->product_id == $product->id ? 'selected' : '' }}>                                                    
                                                            {{ $product->product_name }}                                                 
                                                        </option>                                                   
                                                    @endforeach                                           
                                                </select>                                         
                                            </td>                                                                             
                                            <td>                                         
                                                <input type="text" name="products[{{ $index }}][total_accepted_qty]" class="form-control qty" value="{{ $detail->total_accepted_qty }}" min="1"  style="width: 80px;">                                         
                                            </td>                                         
                                            <td>                                         
                                                <input type="text" name="products[{{ $index }}][total_weight]" class="form-control weight" value="{{ $detail->weight }}" step="any"  style="width: 80px;">                                         
                                            </td>                                         
                                            <td>                                                     
                                                <input type="text" name="products[{{ $index }}][rate]" class="form-control rate" step="any"  style="width: 100px;" id="formattedNumber" oninput="formatNumber(this)">                                         
                                            </td>                                         
                                            <td>                                                    
                                                <input type="text" name="products[{{ $index }}][transportation_amount]" class="form-control transport" step="any"  style="width: 120px;" id="formattedNumber" oninput="formatNumber(this)">                                        
                                            </td>                                         
                                            <td>                                         
                                                <input type="text" name="products[{{ $index }}][total]" class="form-control total" step="any" readonly  style="width: 150px;" id="formattedNumber" oninput="formatNumber(this)">                                          
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
    <div class="col-md-12">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="item_total" class="form-label">Item Amount:</label>
            <input type="text" id="item_total" name="item_total" readonly class="form-control w-100" 
                value="{{ $WeightCalculatorMaster->details->sum(fn($d) => $d->accepted_qty * $d->rate) }}" step="any" 
                oninput="formatNumber(this)">
        </div>
        <div class="col-md-4">
            <label for="total_expense" class="form-label">Additional Expense:</label>
            <input type="text" id="total_expense" name="total_expense" class="form-control w-100" 
                step="0.01" oninput="formatNumber(this)">
        </div>
        <div class="col-md-4">
            <label for="grand_total" class="form-label">Grand Total:</label>
            <input type="text" id="grand_total" name="grand_total" class="form-control w-100" readonly 
                step="any" oninput="formatNumber(this)">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="advance_amount" class="form-label">Advanced Amount:</label>
            <input type="text" id="advance_amount" name="advance_amount" class="form-control w-100" 
                value="{{ $order->advance_amount ?? 0 }}" readonly step="any" oninput="formatNumber(this)">       
        </div>
        <div class="col-md-6">
            <label for="balance_amount" class="form-label">Balance Amount:</label>
            <input type="text" id="balance_amount" name="balance_amount" class="form-control w-100" 
                readonly step="any" oninput="formatNumber(this)">
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
document.getElementById('product-details').addEventListener('input', function (event) {
    const target = event.target;
    if (target.classList.contains('qty') || target.classList.contains('rate') || target.classList.contains('weight') || target.classList.contains('transport')) {
        updateRowTotal(target.closest('tr'));
        calculateTotals(); // Update all totals whenever a row value changes
    }
});

// Event listener for additional expense input
document.getElementById('total_expense').addEventListener('input', function () {
    calculateTotals(false);
});

// Format total_expense when input loses focus
document.getElementById('total_expense').addEventListener('blur', function () {
    let additionalExpense = parseFloat(this.value) || 0;
    this.value = additionalExpense.toFixed(2);
    calculateTotals();
});

function updateRowTotal(row) {
    const totalWeight = parseFloat(row.querySelector('.weight').value) || 0;
    const rate = parseFloat(row.querySelector('.rate').value.replace(/,/g, '')) || 0;
    const transportationAmount = parseFloat(row.querySelector('.transport').value.replace(/,/g, '')) || 0;
    
    // Updated formula: total = (total_weight × rate) + (total_weight × transportation_amount)
    const rowTotal = ((totalWeight * rate) + (totalWeight * transportationAmount)).toFixed(2);
    
    row.querySelector('.total').value = Intl.NumberFormat('en-US').format(rowTotal); // Update row total
    

}

function calculateTotals(formatExpense = true) {
    let itemTotal = 0;

    // Calculate total of all item rows
    document.querySelectorAll('#product-details tr').forEach(row => {
        updateRowTotal(row);
        let rowTotal = parseFloat(row.querySelector('.total').value.replace(/,/g, '')) || 0;
        itemTotal += rowTotal;

        // Format and display row total with commas
        row.querySelector('.total').value = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(rowTotal);
    });

    // Format and display item total
    document.getElementById('item_total').value = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(itemTotal);

    let additionalExpense = parseFloat(document.getElementById('total_expense').value.replace(/,/g, '')) || 0;
    if (formatExpense) {
        document.getElementById('total_expense').value = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(additionalExpense);
    }

    let grandTotalAmount = itemTotal + additionalExpense;
    document.getElementById('grand_total').value = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(grandTotalAmount);

    const advanceAmount = parseFloat(document.getElementById('advance_amount').value.replace(/,/g, '')) || 0;
    let balanceAmount = grandTotalAmount - advanceAmount;
    
    // Format and display balance amount
    document.getElementById('balance_amount').value = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(balanceAmount);
}



</script>
<script>
        document.getElementById('formattedNumber').addEventListener('input', function (e) {
            let value = e.target.value.replace(/,/g, ''); // Remove commas
            // if (!isNaN(value) && value !== '') {
            //     e.target.value = Number(value).toLocaleString(); // Add commas
            // }
        });
    </script>
<script>
        function formatNumber(input) {
            // Remove any existing formatting
            let value = input.value.replace(/,/g, '');
            
            // Convert to a number
            let number = parseFloat(value);
            
            // Format with commas
            if (!isNaN(number)) {
                input.value = new Intl.NumberFormat('en-US').format(number);
            }
        }
    </script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
});
</script>




@endsection

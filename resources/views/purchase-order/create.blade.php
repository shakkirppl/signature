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
                            <h4 class="card-title">Purchase Order</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('purchase-order-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                            <div class="col-md-4">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{ $invoice_no }}" readonly>
                            </div>
                            <div class="col-md-4">
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
                            
                            <div class="col-md-4">
                            <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select name="shipment_id" id="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment No</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                            <label for="sales_no" class="form-label">SalesOrder No:</label>
                                <select name="SalesOrder_id" id="SalesOrder_id" class="form-control" required>
                                 <option value="">Select sales no</option>
                                    @foreach ($SalesOrders as $SalesOrder)
                                    <option value="{{ $SalesOrder->id }}">{{ $SalesOrder->order_no }}</option>
                                    @endforeach
                               </select>
                            </div>
                           
                        </div>
                        <div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                
                <th>Qty</th>
                <th>Male</th>
                <th>Female</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="product-rows">
            <tr>
                <td>
                    <select name="products[0][product_id]" class="form-control product-select" required >
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="products[0][qty]" class="form-control qty" value="0" min="1" required ></td>
                <td><input type="text" name="products[0][male]" class="form-control male" value="0" min="1" required readonly></td>

                <td><input type="text" name="products[0][female]" class="form-control female" value="0" min="1" required  readonly></td>


              
                <td><button type="button" class="btn btn-danger remove-row" >Remove</button></td>
            </tr>
        </tbody>
    </table>
</div>

                        <button type="button" class="btn btn-primary" id="add-row" >Add New Row</button>
                       
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label for="advance_amount" class="form-label">Advance Amount:</label>
                                <input type="text" id="advance_amount" name="advance_amount" class="form-control"
                                  value="" step="0.01" min="0"  id="formattedNumber" oninput="formatNumber(this)" >
               
                            </div>

                            <div class="col-md-3">
    <label for="outstanding_balance" class="form-label">Outstanding Balance:</label>
    <input type="text" id="outstanding_balance" class="form-control" readonly>
    <small id="outstanding_error" class="text-danger" style="display:none;"></small> <!-- Error Message -->
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

    // Function to update male and female fields based on quantity
    function updateGenderDistribution(row) {
        const qtyField = row.querySelector('.qty');
        const maleField = row.querySelector('.male');
        const femaleField = row.querySelector('.female');

        let qty = parseInt(qtyField.value) || 0;
        maleField.value = Math.floor(qty * 0.90); // 75% of quantity
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

</script>
<script>
    // Preload supplier outstanding balances
    var supplierOutstandingBalances = @json($supplierOutstandingBalances);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $('#supplier_id').change(function () {
        var supplierId = $(this).val();
        // Hide any error messages if using an error field
        $('#outstanding_error').hide();

        if (supplierId && supplierOutstandingBalances[supplierId] !== undefined) {
            $('#outstanding_balance').val(supplierOutstandingBalances[supplierId]);
        } else {
            $('#outstanding_balance').val('0.00');
        }
    });
});

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













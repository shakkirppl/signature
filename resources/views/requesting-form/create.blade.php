@extends('layouts.layout')
@section('content')

<style>
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 5px;
        text-align: left;
        font-size: 14px;
    }

    input[type="text"], select {
        width: 100%;
        padding: 5px;
        font-size: 14px;
    }

    .table-responsive {
        overflow-x: auto;
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
                    <!-- Header -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Advance Requesting Form</h4>
                        </div>
                        <div class="col-md-6 text-end">
                        <a href="{{ url('requesting-form-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>

                    <!-- Error Display -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Start -->
                    <form action="{{ route('requesting-form.store') }}" method="POST">                       
                         @csrf

                        <div class="row g-3">
                        <div class="col-md-4">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{ $purchase_order_no }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="invoice_no" class="form-label">Advance Request No</label>
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="{{ $advance_invoice_no }}" readonly >
                            </div>

                            <div class="col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>

                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label">Suppliers</label>
                                <select name="supplier_id" id="supplier_id" class="form-control">
    <option value="">Select Supplier</option>
    @foreach($suppliers as $supplier)
        <option value="{{ $supplier->id }}" data-code="{{ $supplier->code }}">
            {{ $supplier->name }}
        </option>
    @endforeach
</select>

                            </div>

                            <div class="col-md-4">
                                <label for="supplier_no" class="form-label">Supplier Number</label>
                                <input type="text" name="supplier_no" id="supplier_no" class="form-control" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="shipment_id" class="form-label">Shipment No</label>
                                <select name="shipment_id" id="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment No</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="SalesOrder_id" class="form-label">Sales Order No</label>
                                <select name="SalesOrder_id" id="SalesOrder_id" class="form-control" required>
                                    <option value="">Select Sales Order No</option>
                                    @foreach ($SalesOrders as $SalesOrder)
                                        <option value="{{ $SalesOrder->id }}">{{ $SalesOrder->order_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="text" class="form-label">SSF No</label>
                                <input type="text" class="form-control" id="ssf_no" name="ssf_no" required>
                            </div>
                            <div class="col-md-4">
                                <label for="text" class="form-label">Market</label>
                                <input type="text" class="form-control" id="market" name="market" required>
                            </div>
                            <div class="col-md-4">
                            <label for="advance_amount" class="form-label">Advance Amount</label>
                            <input type="text" id="advance_amount" name="advance_amount" class="form-control" oninput="formatNumber(this)">
                            </div>
                           
                               <div class="col-md-4">
                               <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" id="bank_name" name="bank_name" class="form-control">   
                               </div>
                               <div class="col-md-4">
                               <label for="account_name" class="form-label">Account Name</label>
                                <input type="text" id="account_name" name="account_name" class="form-control">   
                               </div>
                               <div class="col-md-4">
                               <label for="account_no" class="form-label">Account No</label>
                                <input type="text" id="account_no" name="account_no" class="form-control">   
                               </div>
                        </div>

                        <!-- Product Table -->
                        <div class="table-responsive mt-4">
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
                                            <select name="products[0][product_id]" class="form-control product-select" required>
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="products[0][qty]" class="form-control qty" value="0" required></td>
                                        <td><input type="text" name="products[0][male]" class="form-control male" value="0" readonly required></td>
                                        <td><input type="text" name="products[0][female]" class="form-control female" value="0" readonly required></td>
                                        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Row Button -->
                        <button type="button" class="btn btn-secondary my-3" id="add-row">Add New Row</button>

                        <!-- Additional Fields -->
                        

                      

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const supplierSelect = document.getElementById('supplier_id');
    const supplierNoField = document.getElementById('supplier_no');

    supplierSelect.addEventListener('change', function () {
        const selectedOption = supplierSelect.options[supplierSelect.selectedIndex];
        const code = selectedOption.getAttribute('data-code');
        supplierNoField.value = code || '';
    });
});
</script>















@extends('layouts.layout')
@section('content')

<style>
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 5px; text-align: left; font-size: 14px; }
    input[type="text"], select { width: 100%; padding: 5px; font-size: 14px; }
    .table-responsive { overflow-x: auto; max-width: 100%; }
    button.remove-row { padding: 3px 8px; font-size: 12px; }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Edit Requesting Form</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('requesting-form.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('requesting-form.update', $requestingForm->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" name="order_no" value="{{ old('order_no', $requestingForm->order_no) }}" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="invoice_no" class="form-label">Advance Request No</label>
                                <input type="text" class="form-control" name="invoice_no" value="{{ old('invoice_no', $requestingForm->invoice_no) }}" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" value="{{ old('date', $requestingForm->date) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="supplier_id" class="form-label">Suppliers</label>
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" data-code="{{ $supplier->code }}"
                                            {{ old('supplier_id', $requestingForm->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="supplier_no" class="form-label">Supplier Number</label>
                                <input type="text" name="supplier_no" id="supplier_no" class="form-control" value="{{ old('supplier_no', $requestingForm->supplier_no) }}" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="shipment_id" class="form-label">Shipment No</label>
                                <select name="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment</option>
                                    @foreach($shipments as $shipment)
                                        <option value="{{ $shipment->id }}" {{ old('shipment_id', $requestingForm->shipment_id) == $shipment->id ? 'selected' : '' }}>
                                            {{ $shipment->shipment_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="SalesOrder_id" class="form-label">Sales Order No</label>
                                <select name="SalesOrder_id" class="form-control" required>
                                    <option value="">Select Sales Order</option>
                                    @foreach($SalesOrders as $order)
                                        <option value="{{ $order->id }}" {{ old('SalesOrder_id', $requestingForm->SalesOrder_id) == $order->id ? 'selected' : '' }}>
                                            {{ $order->order_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="market" class="form-label">Market</label>
                                <input type="text" name="market" class="form-control" value="{{ old('market', $requestingForm->market) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="advance_amount" class="form-label">Advance Amount</label>
                                <input type="text" name="advance_amount" class="form-control" value="{{ old('advance_amount', number_format($requestingForm->advance_amount, 2)) }}" oninput="formatNumber(this)">
                            </div>

                            <div class="col-md-4">
                                <label for="payment_type" class="form-label">Payment Type</label>
                                <select id="payment_type" name="payment_type" class="form-control" onchange="togglePaymentFields()">
                                    <option value="">-- Select --</option>
                                    <option value="Bank" {{ old('payment_type', $requestingForm->payment_type) == 'Bank' ? 'selected' : '' }}>Bank</option>
                                    <option value="Mobile Money" {{ old('payment_type', $requestingForm->payment_type) == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                                </select>
                            </div>

                            <div class="row mt-3" id="bank_fields" style="display: none;">
                                <div class="col-md-4">
                                    <label for="bank_name">Bank Name</label>
                                    <select id="bank_name" name="bank_name" class="form-control">
                                        <option value="">Select Bank</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ old('bank_name', $requestingForm->bank_name) == $bank->id ? 'selected' : '' }}>{{ $bank->bank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Account Name</label>
                                    <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $requestingForm->account_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label>Account No</label>
                                    <input type="text" name="account_no" class="form-control" value="{{ old('account_no', $requestingForm->account_no) }}">
                                </div>
                            </div>

                            <div class="col-md-4 mt-3" id="mobile_money_fields" style="display: none;">
                                <label>Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $requestingForm->phone_number) }}">
                            </div>
                        </div>

                        <!-- Products Table -->
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
                                    @foreach ($requestingForm->details as $index => $detail)
                                        <tr>
                                            <td>
                                                <select name="products[{{ $index }}][product_id]" class="form-control" required>
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->product_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="products[{{ $index }}][qty]" class="form-control qty" value="{{ $detail->qty }}" required></td>
                                            <td><input type="text" name="products[{{ $index }}][male]" class="form-control male" value="{{ $detail->male }}" readonly required></td>
                                            <td><input type="text" name="products[{{ $index }}][female]" class="form-control female" value="{{ $detail->female }}" readonly required></td>
                                            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-secondary my-3" id="add-row">Add New Row</button>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function formatNumber(input) {
        let value = input.value.replace(/,/g, '');
        let number = parseFloat(value);
        if (!isNaN(number)) {
            input.value = new Intl.NumberFormat('en-US').format(number);
        }
    }

    function togglePaymentFields() {
        const type = document.getElementById("payment_type").value;
        document.getElementById("bank_fields").style.display = type === "Bank" ? "flex" : "none";
        document.getElementById("mobile_money_fields").style.display = type === "Mobile Money" ? "flex" : "none";
    }

    document.addEventListener('DOMContentLoaded', function () {
        togglePaymentFields();

        const supplierSelect = document.getElementById('supplier_id');
        const supplierNoField = document.getElementById('supplier_no');
        const selectedOption = supplierSelect.options[supplierSelect.selectedIndex];
        supplierNoField.value = selectedOption.getAttribute('data-code') || '';

        const productRows = document.getElementById('product-rows');
        const addRowBtn = document.getElementById('add-row');

        addRowBtn.addEventListener('click', function () {
            const rowCount = productRows.children.length;
            const newRow = `
                <tr>
                    <td>
                        <select name="products[${rowCount}][product_id]" class="form-control" required>
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="products[${rowCount}][qty]" class="form-control qty" value="0" required></td>
                    <td><input type="text" name="products[${rowCount}][male]" class="form-control male" value="0" readonly required></td>
                    <td><input type="text" name="products[${rowCount}][female]" class="form-control female" value="0" readonly required></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>`;
            productRows.insertAdjacentHTML('beforeend', newRow);
        });

        productRows.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });

        productRows.addEventListener('input', function (e) {
            if (e.target.classList.contains('qty')) {
                const row = e.target.closest('tr');
                const qty = parseInt(e.target.value) || 0;
                row.querySelector('.male').value = Math.floor(qty * 0.90);
                row.querySelector('.female').value = qty - Math.floor(qty * 0.90);
            }
        });
    });
</script>
@endpush

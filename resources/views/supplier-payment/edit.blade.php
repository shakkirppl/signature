@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Edit Supplier Payment</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('supplier-payment/list') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('supplier-payment.update', $supplierPayment->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <!-- Payment Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="date" value="{{ $supplierPayment->date }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Bank Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="bank_name" value="{{ $supplierPayment->bank_name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Payment Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="payment_type" id="payment_type" onchange="togglePaymentFields()" required>
                                            <option value="Cash" {{ $supplierPayment->payment_type == 'Cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="Cheque" {{ $supplierPayment->payment_type == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                            <option value="Transfer" {{ $supplierPayment->payment_type == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Conditional Fields -->
                        <div id="cheque_details" class="row mt-3 {{ $supplierPayment->payment_type == 'Cheque' ? '' : 'd-none' }}">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Cheque No</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="cheque_no" value="{{ $supplierPayment->cheque_no }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Cheque Date</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="cheque_date" value="{{ $supplierPayment->cheque_date }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="transfer_reference" class="row mt-3 {{ $supplierPayment->payment_type == 'Transfer' ? '' : 'd-none' }}">
                           
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Transaction Reference</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="trans_reference" value="{{ $supplierPayment->trans_reference }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment To and Notes -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Payment To</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="payment_to" required>
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ $supplierPayment->payment_to == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Notes</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="notes">{{ $supplierPayment->notes }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Fields -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Total Amount</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="outsatnding_amount" value="{{ $supplierPayment->outsatnding_amount }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Allocated Amount</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="allocated_amount" value="{{ $supplierPayment->allocated_amount }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Balance Amount</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="balance" value="{{ $supplierPayment->balance }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Table -->
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No</th>
                                        <th>Date</th>
                                        <th>PI No</th>
                                        <th>Amount</th>
                                        <th>Balance Amount</th>
                                        <th>Paid</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentTableBody">
                                    @foreach ($supplierPayment->details as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><input type="date" class="form-control" name="payment_date[]" value="{{ $detail->payment_date }}"></td>
                                            <td><input type="text" class="form-control" name="pi_no[]" value="{{ $detail->pi_no }}"></td>
                                            <td><input type="number" class="form-control amount" name="amount[]" value="{{ $detail->amount }}"></td>
                                            <td><input type="number" class="form-control balance-amount" name="balance_amount[]" value="{{ $detail->balance_amount }}"></td>
                                            <td><input type="number" class="form-control paid" name="paid[]" value="{{ $detail->paid }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-4">
                            <div class="col-sm-12 text-end">
                                <button type="button" class="btn btn-secondary" onclick="addRow()">Add Row</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    let rowCount = {{ count($supplierPayment->details) }};  // Initialize rowCount with the number of existing rows.

    // Function to add a new row to the table
    function addRow() {
        rowCount++; // Increment row count when a new row is added
        const tableBody = document.getElementById('paymentTableBody');

        // Create a new row HTML string
        const row = `<tr>
                        <td>${rowCount}</td>
                        <td><input type="date" class="form-control" name="payment_date[]"></td>
                        <td><input type="text" class="form-control" name="pi_no[]"></td>
                        <td><input type="number" class="form-control amount" name="amount[]" oninput="updateTableTotals()"></td>
                        <td><input type="number" class="form-control balance-amount" name="balance_amount[]"></td>
                        <td><input type="number" class="form-control paid" name="paid[]" oninput="updateTableTotals()"></td>
                    </tr>`;

        // Append the new row to the table body
        tableBody.insertAdjacentHTML('beforeend', row);
    }

    // Function to update the totals (balance and paid) in the table when amounts are entered
    function updateTableTotals() {
        const amounts = document.querySelectorAll('.amount');
        const paidAmounts = document.querySelectorAll('.paid');
        const balanceAmounts = document.querySelectorAll('.balance-amount');

        amounts.forEach((amount, index) => {
            const paid = parseFloat(paidAmounts[index].value) || 0;
            const amountValue = parseFloat(amount.value) || 0;
            balanceAmounts[index].value = amountValue - paid; // Update balance amount
        });
    }
    
    // Call this function when the page is loaded to handle dynamic row additions
    document.addEventListener('DOMContentLoaded', function () {
        togglePaymentFields(); // Maintain payment type field visibility
    });
</script>
<script>
    function togglePaymentFields() {
        const paymentType = document.getElementById('payment_type').value;
        const transferReference = document.getElementById('transfer_reference');
        const chequeDetails = document.getElementById('cheque_details');

        transferReference.classList.add('d-none');
        chequeDetails.classList.add('d-none');

        if (paymentType === 'Transfer') {
            transferReference.classList.remove('d-none');
        } else if (paymentType === 'Cheque') {
            chequeDetails.classList.remove('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        togglePaymentFields();
    });
</script>
@endsection

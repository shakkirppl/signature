@extends('layouts.layout')
@section('content')
<style>
    .wide-select {
        width: 200px !important; 
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Customer Payment</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('customer-payment-index') }}" class="backicon">
                                <i class="mdi mdi-backburger"></i>
                            </a>
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

                    <form action="{{ route('customer-payment.store') }}" method="POST">
                    @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Payment Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="payment_type" id="payment_type" required>
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conditional Fields for Cheque and Transfer -->
                        <div id="cheque_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Cheque Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="cheque_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Cheque No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cheque_no">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="transfer_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Transfer ID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="transfer_id">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="bank_field" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Bank</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="bank_name" >
                                                <option value="">Select Bank</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->bank_name }}">{{ $bank->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Payment To</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="payment_to" id="payment_to" required>
                                            <option value="">Select customers</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Balance Amount</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="balance" name="balance" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Outstanding Amount</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="outstanding_amount" name="outstanding_amount" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Allocated Amount</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="allocated_amount" name="allocated_amount" readonly>
                                        </div>
                                    </div>
                                </div>
                        </div>

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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentTableBody">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th><span id="total_amount">0.00</span></th><input type="hidden" name="total_amount" id="total_amount_input">
                                        <th><span id="total_balance">0.00</span></th><input type="hidden" name="total_balance" id="total_balance_input">
                                        <th><span id="total_paid">0.00</span></th><input type="hidden" name="total_paidAmount" id="total_paidAmount">
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Set today's date automatically on page load
        $('#payment_date').val(new Date().toISOString().split('T')[0]);

        // Show/hide fields based on payment type selection
        $('#payment_type').change(function () {
            let type = $(this).val();
            $('#cheque_fields').toggle(type === 'Cheque');
            $('#transfer_fields').toggle(type === 'Transfer');
            $('#bank_field').toggle(type === 'Cheque'); // Show only for Cheque
        });

        $(document).ready(function () {
            $('#payment_to').change(function () {
        var customerId = $(this).val();
        var baseUrl = "{{ url('get-customer-sales') }}"; // Endpoint to fetch sales data

        if (customerId) {
            $.ajax({
                url: baseUrl,
                type: 'GET',
                data: { customer_id: customerId },
                dataType: 'json',
                success: function (response) {
                    console.log("Response Data:", response);
                    $('#paymentTableBody').empty();
                    let outstandingAmount = 0;

                    if (response.length === 0) {
                        $('#paymentTableBody').append('<tr><td colspan="7" class="text-center">No records found</td></tr>');
                    } else {
                        $.each(response, function (index, item) {
                            outstandingAmount += parseFloat(item.balance_amount || 0);

                            $('#paymentTableBody').append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td><input type="date" class="form-control" name="date[]" value="${item.date}" readonly></td>
                                    <td>
                                        <input type="hidden" name="sales_payment_id[]" value="${item.sales_payment_id}">
                                        <input type="text" class="form-control" name="pi_no[]" value="${item.order_no}" readonly  style="width: 100px;">
                                    </td>
                                    <td><input type="number" class="form-control amount" name="amount[]" value="${item.grand_total}" readonly style="width: 150px;">></td>
                                    <td><input type="number" class="form-control balance_amount" name="balance_amount[]" value="${item.balance_amount}" readonly ></td>
                                    <td><input type="number" class="form-control paid" name="paid[]" min="0" step="0.01" value="0.00" oninput="updateTotals()"  style="width: 100px;"></td>
                                    <td><button type="button" class="btn btn-danger removeRow">Remove</button></td>
                                </tr>
                            `);
                        });
                    }

                    $('#outstanding_amount').val(outstandingAmount.toFixed(2));
                    updateTotals();
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching customer data:", error);
                    $('#paymentTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>');
                }
            });
        }
    });

    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
        updateTotals();
    });

    $(document).on('input', '.paid', function () {
        updateTotals();
    });

    function updateTotals() {
        let totalPaid = 0;
        $('.paid').each(function () {
            let paidValue = parseFloat($(this).val()) || 0;
            totalPaid += paidValue;
        });

        let outstandingAmount = parseFloat($('#outstanding_amount').val()) || 0;
        let balance = outstandingAmount - totalPaid;

        $('#allocated_amount').val(totalPaid.toFixed(2));
        $('#balance').val(balance.toFixed(2));
    }
});
});

    
</script>
<script>
  function updateTotals() {
    let totalPaid = 0, totalAmount = 0, totalBalance = 0;

    $('.paid').each(function () {
        let paidValue = parseFloat($(this).val()) || 0;
        totalPaid += paidValue;
    });

    $('.amount').each(function () {
        let amountValue = parseFloat($(this).val()) || 0;
        totalAmount += amountValue;
    });

    $('.balance_amount').each(function () {
        let balanceValue = parseFloat($(this).val()) || 0;
        totalBalance += balanceValue;
    });

    let outstandingAmount = parseFloat($('#outstanding_amount').val()) || 0;
    let balance = outstandingAmount - totalPaid;

    // Update input values before form submission
    $('#allocated_amount').val(totalPaid.toFixed(2));
    $('#balance').val(balance.toFixed(2));

    // Update footer values
    $('#total_amount').text(totalAmount.toFixed(2));
    $('#total_balance').text(totalBalance.toFixed(2));
    $('#total_paid').text(totalPaid.toFixed(2));

    // Ensure hidden inputs are updated
    $('#total_amount_input').val(totalAmount.toFixed(2));
    $('#total_balance_input').val(totalBalance.toFixed(2));
    $('#total_paidAmount').val(totalPaid.toFixed(2));
}

$(document).on('input', '.paid', function () {
    updateTotals();
});

</script>

@endsection


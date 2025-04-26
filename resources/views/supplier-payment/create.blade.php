@extends('layouts.layout')
@section('content')
<style>
.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table th, .table td {
    padding: 5px;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
}

.table input {
    width: 100px !important; 
    font-size: 14px;
    padding: 3px;
}

.table-responsive {
    overflow-x: auto;
}

button.remove-row {
    padding: 3px 6px;
    font-size: 12px;
}


</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Supplier Payment</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('supplier-payment-index') }}" class="backicon">
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

                    <form action="{{ route('supplier-payment.store') }}" method="POST">
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
                                           <option value="cash">Cash</option>
                                           <option value="bank">Bank</option>
                                           <option value="mobilemoney">Mobile Money</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div id="cheque_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Cheque Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="cheque_date">
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Cheque No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cheque_no">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- <div id="transfer_fields" style="display: none;">
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
                        </div> -->
                        <div id="bank_field" style="display: none;">
                       
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

                        <div class="row">
                        
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Shipment No</label>
                                    <div class="col-sm-9">
                                    <select class="form-control" name="shipment_id"  id="shipment">
                                           <option value="">Select Shipment</option>
                                                @foreach ($shipments as $shipment)
                                                   <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Payment To</label>
                                    <div class="col-sm-9">
                                    <select class="form-control" name="payment_to" id="payment_to" required>
                                      <option value="">Select Supplier</option>
                                   </select>

                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Balance Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="balance" name="balance" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Outstanding Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="outstanding_amount" name="outstanding_amount" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Allocated Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="allocated_amount" name="allocated_amount" readonly>
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
                                        <th><span id="total_amount">0.00</span></th><input type="hidden" name="total_amount" id="total_amount_input"></th>
                                        <th><span id="total_balance">0.00</span></th><input type="hidden" name="total_balance" id="total_balance_input"></th>
                                        <th><span id="total_paid">0.00</span><input type="hidden" name="total_paidAmount" id="total_paidAmount"></th>
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
    function formatNumber(num) {
    if (isNaN(num)) num = 0;
    return Number(num).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function parseFormattedNumber(str) {
    if (!str) return 0;
    return parseFloat(str.toString().replace(/,/g, '')) || 0;
}
function formatPaidAmount(element) {
    let rawValue = element.value.replace(/,/g, '');
    if (rawValue) {
        let formattedValue = parseFloat(rawValue).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        element.value = formattedValue;
    }
    updateTotals(); // After formatting, recalculate totals
}


$(document).ready(function () {

    function formatNumber(num) {
        return parseFloat(num).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function parseFormattedNumber(num) {
        return parseFloat(num.replace(/,/g, '')) || 0;
    }

    function toggleBankField() {
        var type = $('#payment_type').val();
        if (type === 'bank') {
            $('#bank_field').show();
            $('#bank_name').attr('required', true);
        } else {
            $('#bank_field').hide();
            $('#bank_name').removeAttr('required');
        }
    }

    toggleBankField();

    $('#payment_type').on('change', function () {
        toggleBankField();
    });

    $('#shipment').change(function () {
        var shipmentId = $(this).val();
        var supplierDropdown = $('#payment_to');
        supplierDropdown.empty().append('<option value="">Select Supplier</option>');

        if (shipmentId) {
            $.ajax({
                url: "{{ url('get-suppliers-by-shipment-payment') }}",
                type: 'GET',
                data: { shipment_id: shipmentId },
                dataType: 'json',
                success: function (response) {
                    $.each(response, function (index, supplier) {
                        supplierDropdown.append(`<option value="${supplier.id}">${supplier.name}</option>`);
                    });
                },
                error: function () {
                    console.error("Error fetching suppliers.");
                }
            });
        }
    });

    $('#payment_to').change(function () {
        var supplierId = $(this).val();
        var baseUrl = "{{ url('get-supplier-conformations') }}";

        if (supplierId) {
            $.ajax({
                url: baseUrl,
                type: 'GET',
                data: { supplier_id: supplierId },
                dataType: 'json',
                success: function (response) {
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
                                        <input type="hidden" name="conformation_id[]" value="${item.conformation_id}">
                                        <input type="text" class="form-control" name="pi_no[]" value="${item.invoice_number}" readonly>
                                    </td>
                                    <td><input type="text" class="form-control amount" name="amount[]" value="${formatNumber(item.total_amount)}" readonly style="width: 180px;"></td>
                                    <td><input type="text" class="form-control balance_amount" name="balance_amount[]" value="${formatNumber(item.balance_amount)}" readonly></td>
                                    <td><input type="text" class="form-control paid" name="paid[]" min="0" step="0.0" value="0.00" oninput="updateTotals()" onblur="formatPaidAmount(this)" autocomplete="off" ></td>
                                    <td><button type="button" class="btn btn-danger removeRow">Remove</button></td>
                                </tr>
                            `);
                        });
                    }

                    $('#outstanding_amount').val(outstandingAmount.toFixed(2));
                    updateTotals();
                },
                error: function () {
                    console.error("Error fetching supplier data.");
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
        let val = parseFormattedNumber($(this).val());
        $(this).val(formatNumber(val));
        updateTotals();
    });

    function updateTotals() {
    let totalPaid = 0, totalAmount = 0, totalBalance = 0;

    $('.paid').each(function () {
        totalPaid += parseFormattedNumber($(this).val());
    });

    $('.amount').each(function () {
        totalAmount += parseFormattedNumber($(this).val());
    });

    $('.balance_amount').each(function () {
        totalBalance += parseFormattedNumber($(this).val());
    });

    let outstandingAmount = parseFormattedNumber($('#outstanding_amount').val());
    let balance = outstandingAmount - totalPaid;

    // Set formatted values into inputs
    $('#allocated_amount').val(formatNumber(totalPaid));
    $('#balance').val(formatNumber(balance));

    // If you show total amounts elsewhere also
    $('#total_amount').text(formatNumber(totalAmount));
    $('#total_balance').text(formatNumber(totalBalance));
    $('#total_paid').text(formatNumber(totalPaid));

    // Hidden values if required
    $('#total_amount_input').val(totalAmount.toFixed(2));
    $('#total_balance_input').val(totalBalance.toFixed(2));
    $('#total_paidAmount').val(totalPaid.toFixed(2));
}

    // Set today's date by default
    const dateInput = document.getElementById('payment_date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;

});
</script>

@endsection



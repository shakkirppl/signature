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

                      
                        <div id="bank_field" style="display: none;">
                       
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Bank</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="bank_name" >
                                        @foreach ($banks as $bank)
                                  <option value="{{ $bank->id }}" 
                                 {{ $bank->currency == 'Shilling' ? 'selected' : '' }}>
                                    {{ $bank->bank_name }}
                                  </option>
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
    return new Intl.NumberFormat('sw-TZ', { 
        minimumFractionDigits: 2, 
        maximumFractionDigits: 2 
    }).format(num);
}
function parseFormattedNumber(str) {
    if (!str) return 0;
    // Remove dot (.) as thousand separator, replace comma (,) with dot (.)
    return parseFloat(str.toString().replace(/\./g, '').replace(',', '.')) || 0;
}

$(document).ready(function () {

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
                            $('#paymentTableBody').append(appendRow(index, item));
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
        let inputVal = $(this).val();

        // Allow only numbers and decimal points
        inputVal = inputVal.replace(/[^0-9.]/g, '');

        // Handle multiple decimals (keep only first one)
        let parts = inputVal.split('.');
        if (parts.length > 2) {
            inputVal = parts[0] + '.' + parts.slice(1).join('');
        }

        $(this).val(inputVal);

        let numericVal = parseFloat(inputVal) || 0;

        // Show formatted value below input
        let formattedValue = formatNumber(numericVal);
        $(this).next('.formatted-display').text(formattedValue);

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

        // **Allocate amount must be equal to total paid**
        $('#allocated_amount').val(totalPaid.toFixed(2));

        $('#balance').val(formatNumber(balance));
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
$('form').on('submit', function () {
    $('.amount, .balance_amount, .paid').each(function () {
        let plainValue = parseFormattedNumber($(this).val()).toFixed(2);
        $(this).val(plainValue);
    });

    let allocPlain = parseFormattedNumber($('#allocated_amount').val()).toFixed(2);
    $('#allocated_amount').val(allocPlain);

    let balancePlain = parseFormattedNumber($('#balance').val()).toFixed(2);
    $('#balance').val(balancePlain);
});



// Function to return dynamic table row
function appendRow(index, item) {
    return `
        <tr>
            <td>${index + 1}</td>
            <td><input type="date" class="form-control" name="date[]" value="${item.date}" readonly></td>
            <td>
                <input type="hidden" name="conformation_id[]" value="${item.conformation_id}">
                <input type="text" class="form-control" name="pi_no[]" value="${item.invoice_number}" readonly>
            </td>
            <td><input type="text" class="form-control amount" name="amount[]" value="${formatNumber(item.total_amount)}" readonly style="width: 180px;"></td>
            <td><input type="text" class="form-control balance_amount" name="balance_amount[]" value="${formatNumber(item.balance_amount)}" readonly></td>
            <td>
                <input type="text" class="form-control paid" name="paid[]" min="0" step="0.01" value="0.00">
                <div class="formatted-display text-muted" style="font-size: 12px; margin-top: 2px;">0.00 </div>
            </td>
            <td><button type="button" class="btn btn-danger removeRow">Remove</button></td>
        </tr>
    `;
}
</script>

@endsection







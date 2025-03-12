@extends('layouts.layout')
@section('content')
<style>
    .wide-select {
        width: 200px !important; 
    }

    .table {
    border-collapse: collapse;
    width: 100%;
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
                            <h4 class="card-title">Supplier Payment</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('supplier-payment/list') }}" class="backicon">
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
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

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
$(document).ready(function () {
    // Fetch suppliers when a shipment is selected
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
                    // console.log(response);
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

    // Fetch supplier payment details when supplier is selected
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
                                    <td><input type="date" class="form-control" name="date[]" value="${item.date}" readonly style="width: 150px;"></td>
                                    <td>
                                        <input type="hidden" name="conformation_id[]" value="${item.conformation_id}">
                                        <input type="text" class="form-control" name="pi_no[]" value="${item.invoice_number}" readonly style="width: 150px;">
                                    </td>
                                    <td><input type="number" class="form-control amount" name="amount[]" value="${item.total_amount}" readonly style="width: 180px;"></td>
                                    <td><input type="number" class="form-control balance_amount" name="balance_amount[]" value="${item.balance_amount}" readonly style="width: 150px;"></td>
                                    <td><input type="number" class="form-control paid" name="paid[]" min="0" step="0.01" value="0.00" oninput="updateTotals()" style="width: 150px;"></td>
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

    // Remove row and update totals
    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
        updateTotals();
    });

    // Update total amounts dynamically
    $(document).on('input', '.paid', function () {
        updateTotals();
    });

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

        // Update the fields
        $('#allocated_amount').val(totalPaid.toFixed(2));
        $('#balance').val(balance.toFixed(2));

        // Update the footer totals
        $('#total_amount').text(totalAmount.toFixed(2));
        $('#total_balance').text(totalBalance.toFixed(2));
        $('#total_paid').text(totalPaid.toFixed(2));

        // Update the hidden input fields
        $('#total_amount_input').val(totalAmount.toFixed(2));
        $('#total_balance_input').val(totalBalance.toFixed(2));
        $('#total_paidAmount').val(totalPaid.toFixed(2));
    }
});



</script>

@endsection


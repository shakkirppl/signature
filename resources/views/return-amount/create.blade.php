@extends('layouts.layout')
@section('content')
<style>
    .required:after {
        content: " *";
        color: red;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Return Payment</h4>
                    <div class="col-md-12 heading mb-3">
                        <a href="{{ route('return-payment.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('return-payment.store') }}" method="POST">
                        @csrf

                        <div class="card mb-4 border">
                            <div class="card-header bg-light"></div>
                            <div class="card-body row g-3">
                                <div class="col-md-4">
                                    <label class="required">Date:</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>
                                <div class="col-md-4">
    <label class="required">Return Type:</label>
    <select name="type" id="type" class="form-control" required>
        <option value="">Select Type</option>
        <option value="opening balance">Opening Balance</option>
        <option value="transaction">Transaction</option>
    </select>
</div><div class="col-md-4" id="shipment_section">
    <label class="required">Shipment No:</label>
    <select name="shipment_id" id="shipment_id" class="form-control">
        <option value="">Select Shipment</option>
        @foreach($shipments as $shipment)
            <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label class="required">Supplier:</label>
    <select name="supplier_id" id="supplier_id" class="form-control select2" required>
        <option value="">Select Supplier</option>
        {{-- Options will be appended via JS --}}
    </select>
</div>



                                <div class="col-md-4 mt-3">
                                    <label for="outstanding_balance">Balance Amount:</label>
                                    <input type="text" id="outstanding_balance" class="form-control" readonly>
                                    <small id="outstanding_error" class="text-danger" style="display:none;"></small>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label>Return Amount:</label>
                                    <input type="text" id="retrun_amount" name="retrun_amount" class="form-control">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Auto-set today's date
        const dateInput = document.getElementById('date');
        let today = new Date().toISOString().split('T')[0];
        dateInput.value = today;

        // Load suppliers based on shipment
        $('#shipment_id').change(function () {
            var shipmentId = $(this).val();
            if (shipmentId) {
                $.ajax({
                    url: "{{ route('get.suppliers.by.shipment') }}",
                    type: "GET",
                    data: { shipment_id: shipmentId },
                    success: function (response) {
                        $('#supplier_id').html('<option value="">Select Supplier</option>');
                        $.each(response.suppliers, function (index, supplier) {
                            $('#supplier_id').append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
                        });
                        $('#outstanding_balance').val('0.00');
                    },
                    error: function () {
                        alert('Error loading suppliers');
                    }
                });
            }
        });

        // Load outstanding when supplier is selected
        $('#supplier_id').change(function () {
            var supplierId = $(this).val();
            if (supplierId) {
                $.ajax({
                    url: "{{ route('get.supplier.outstanding') }}",
                    type: "GET",
                    data: { supplier_id: supplierId },
                    success: function (response) {
                        $('#outstanding_balance').val(response.balance);
                    },
                    error: function () {
                        $('#outstanding_balance').val('0.00');
                    }
                });
            } else {
                $('#outstanding_balance').val('0.00');
            }
        });
    });

    $('#type').change(function () {
    var type = $(this).val();

    if (type === 'opening_balance') {
        $('#shipment_section').hide();
        // Load all suppliers
        $.ajax({
            url: "{{ route('get.all.suppliers') }}",
            type: "GET",
            success: function (response) {
                $('#supplier_id').html('<option value="">Select Supplier</option>');
                $.each(response.suppliers, function (index, supplier) {
                    $('#supplier_id').append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
                });
                $('#outstanding_balance').val('0.00');
            }
        });
    } else if (type === 'transaction') {
        $('#shipment_section').show();
        $('#supplier_id').html('<option value="">Select Supplier</option>');
        $('#outstanding_balance').val('0.00');
    } else {
        $('#shipment_section').hide();
        $('#supplier_id').html('<option value="">Select Supplier</option>');
        $('#outstanding_balance').val('0.00');
    }
});

$('#type').trigger('change'); // default behavior on load


</script>
<script>
    $(document).ready(function() {
        $('#supplier_id').select2({
            placeholder: 'Select Supplier',
            allowClear: true,
            width: '100%' // Makes it full width
        });
    });
</script>

@endsection

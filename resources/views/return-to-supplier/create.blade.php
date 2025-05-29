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
                    <h4 class="card-title">Supplier Final Payment</h4>
                    <div class="col-md-12 heading mb-3">
                        <a href="{{ route('return-to-supplier.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('return-to-supplier.store') }}" method="POST">
                        @csrf

                        <div class="card mb-4 border">
                            <div class="card-header bg-light"></div>
                            <div class="card-body row g-3">
                                <div class="col-md-4">
                                    <label class="required">Date:</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="required">Supplier:</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                                        <option value="">Select Supplier</option>
                                        {{-- Will be populated by JS --}}
                                    </select>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="outstanding_balance">Balance Amount:</label>
<input type="text" id="outstanding_balance" class="form-control text-success font-weight-bold" readonly>
                                    <small id="outstanding_error" class="text-danger" style="display:none;"></small>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label> Amount:</label>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Set today's date
        $('#date').val(new Date().toISOString().split('T')[0]);

        // Load all suppliers on page load
        $.ajax({
            url: "{{ route('getall.suppliers') }}",
            type: "GET",
            success: function (response) {
                $('#supplier_id').html('<option value="">Select Supplier</option>');
                $.each(response.suppliers, function (index, supplier) {
                    $('#supplier_id').append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
                });
            },
            error: function () {
                alert('Failed to load suppliers.');
            }
        });

        // Load outstanding balance when supplier selected
        $('#supplier_id').change(function () {
            var supplierId = $(this).val();
            if (supplierId) {
                $.ajax({
                    url: "{{ route('getsupplier.outstanding') }}",
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

        // Enable Select2 for suppliers
        $('#supplier_id').select2({
            placeholder: 'Select Supplier',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection

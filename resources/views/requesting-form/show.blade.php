@extends('layouts.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Requesting Form Details</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('requesting-form-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Request No:</label>
                            <input type="text" class="form-control" value="{{ $form->invoice_no }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date:</label>
                            <input type="text" class="form-control" value="{{ $form->date }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Supplier:</label>
                            <input type="text" class="form-control" value="{{ $form->supplier->name ?? 'N/A' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Advance Amount:</label>
                            <input type="text" class="form-control" value="{{ $form->advance_amount }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Status:</label>
                            <input type="text" class="form-control" value="{{ ucfirst($form->status) }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Shipment No:</label>
                            <input type="text" class="form-control" value="{{ $form->shipment->shipment_no ?? 'N/A' }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Requested By:</label>
                            <input type="text" class="form-control" value="{{ $form->user->name ?? 'N/A' }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Market:</label>
                            <input type="text" class="form-control" value="{{ $form->market }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Type:</label>
                            <input type="text" class="form-control" value="{{ $form->payment_type }}">
                        </div>
                    </div>

                    <div id="bank-details" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Bank Name:</label>
                                <input type="text" class="form-control" value="{{ $form->bank->bank_name }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Account Name:</label>
                                <input type="text" class="form-control" value="{{ $form->account_name }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Account No:</label>
                                <input type="text" class="form-control" value="{{ $form->account_no }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div id="mobile-money-details" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Phone Number:</label>
                                <input type="text" class="form-control" value="{{ $form->phone_number }}" readonly>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Male</th>
                                <th>Female</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($form->products as $detail)
                            <tr>
                                <td>{{ $detail->product->product_name ?? 'N/A' }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>{{ $detail->male }}</td>
                                <td>{{ $detail->female }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No products found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to toggle visibility based on payment type
    document.addEventListener('DOMContentLoaded', function () {
        const paymentType = "{{ $form->payment_type }}";
        
        if (paymentType === 'Bank') {
            document.getElementById('bank-details').style.display = 'block';
            document.getElementById('mobile-money-details').style.display = 'none';
        } else if (paymentType === 'Mobile Money') {
            document.getElementById('mobile-money-details').style.display = 'block';
            document.getElementById('bank-details').style.display = 'none';
        }
    });
</script>

@endsection

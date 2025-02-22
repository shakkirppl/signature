@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h4 class="card-title">Customer Payment Details</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('customer-payment/list') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>

                    <div class="row">
                        @if ($customerPayment->payment_type === 'Cheque' || $customerPayment->payment_type === 'Transfer')
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                @if ($customerPayment->payment_type === 'Cheque')
                                <tr>
                                    <th>Cheque No</th>
                                    <td>{{ $customerPayment->cheque_no }}</td>
                                </tr>
                                <tr>
                                    <th>Cheque Date</th>
                                    <td>{{ $customerPayment->cheque_date }}</td>
                                </tr>
                                @endif
                                @if ($customerPayment->payment_type === 'Transfer')
                                <tr>
                                    <th>Transaction Reference</th>
                                    <td>{{ $customerPayment->trans_reference }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        @endif
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S No</th>
                                            <th>Invoice No</th>
                                            <th>Amount</th>
                                            <th>Balance Amount</th>
                                            <th>Paid</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($customerPayment->details as $index => $detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $detail->pi_no }}</td>
                                            <td>{{ number_format($detail->amount, 2) }}</td>
                                            <td>{{ number_format($detail->balance_amount, 2) }}</td>
                                            <td>{{ number_format($detail->paid, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No payment details available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Notes</th>
                            <td>{{ $customerPayment->notes }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">Sales  Details</h4>
                        </div>
                        <div class="col-md-6 text-right">
                        <a href="{{ route('sales_payment.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <p><strong>Order No:</strong> {{ $SalesPayment->order_no }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Date:</strong> {{ $SalesPayment->date }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Customer:</strong> {{ $SalesPayment->customer->customer_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Sales No:</strong> {{ $SalesPayment->SalesOrder->order_no ?? 'N/A' }}</p>
                        </div>
                    </div>

                  <br>

                  <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($SalesPayment->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->product_name ?? 'N/A' }}</td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>{{ number_format($detail->rate, 2) }}</td>
                                    <td>{{ number_format($detail->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
</div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label><strong>Grand Total:</strong></label>
                            <input type="text" class="form-control" value="${{ number_format($SalesPayment->grand_total, 2) }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Advance Paid:</strong></label>
                            <input type="text" class="form-control" value="${{ number_format($SalesPayment->advance_amount, 2) }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Balance Amount:</strong></label>
                            <input type="text" class="form-control" value="${{ number_format($SalesPayment->balance_amount, 2) }}" readonly>
                        </div>
                    </div>

                   

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

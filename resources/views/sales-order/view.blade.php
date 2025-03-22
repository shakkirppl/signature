@extends('layouts.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Sales Order Details</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('goodsout-order-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="order_no" class="form-label">Order No:</label>
                            <input type="text" class="form-control" id="order_no" value="{{ $salesOrder->order_no }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="date" class="form-label">Date:</label>
                            <input type="text" class="form-control" id="date" value="{{ $salesOrder->date }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="customer" class="form-label">Customer:</label>
                            <input type="text" class="form-control" id="customer" value="{{ $salesOrder->customer->customer_name }}" readonly>
                        </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($salesOrder->details as $detail)
                            <tr>
                            <td>{{ optional($detail->product)->product_name ?? 'N/A' }}</td>
                            <td>{{ $detail->qty }}</td>
                                <td>{{ number_format($detail->rate, 2) }}</td>
                                <td>{{ number_format($detail->total, 2) }}</td>
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
    </div>
</div>
@endsection

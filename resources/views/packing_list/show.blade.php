@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sales Packing List Details</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order No:</strong> {{ $packing->packing_no }}</p>
                            <p><strong>Date:</strong> {{ $packing->date }}</p>
                            <p><strong>Customer:</strong> {{ $packing->customer->customer_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Shipping Mode:</strong> {{ $packing->shipping_mode }}</p>
                            <p><strong>Shipping Agent:</strong> {{ $packing->shipping_agent }}</p>
                            <p><strong>Currency:</strong> {{ strtoupper($packing->currency) }}</p>
                        </div>
                    </div>

                    <h5>Product Details</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Packaging</th>
                                <th>Weight</th>
                                <th>Par</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packing->details as $detail)
                            <tr>
                                <td>{{ $detail->product->product_name ?? 'N/A' }}</td>
                                <td>{{ $detail->packaging }}</td>
                                <td>{{ $detail->weight }}</td>
                                <td>{{ $detail->par }}</td>
                                <td>{{ number_format($detail->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                   
                    <p><strong> Total Weight:</strong> {{ number_format($packing->sum_total, 2) }}</p>
                    <p><strong>Net Weight:</strong> {{ $packing->net_weight }}</p>
                    <p><strong>Gross Weight:</strong> {{ $packing->gross_weight }}</p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

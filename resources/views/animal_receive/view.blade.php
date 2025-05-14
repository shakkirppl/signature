@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Animal Receive Notes</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order No:</strong> {{ $inspection->order_no }}</p>
                            <p><strong>Inspection No:</strong> {{ $inspection->inspection_no }}</p>
                            <p><strong>Date:</strong> {{ $inspection->date }}</p>
                            <p><strong>Supplier:</strong> {{ $inspection->supplier->name }}</p>
                              <p><strong>Mark:</strong> {{ $inspection->mark }}</p>
                              
                              @if($inspection->signature)
    <img src="{{ asset('uploads/signatures/' . $inspection->signature) }}" width="200">
@endif
                        </div>
                        
                    </div>

                    <h5 class="mt-4">Products</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Received Qty</th>
                                    <th>Male Accepted</th>
                                    <th>Female Accepted</th>
                                    <th>Male Rejected</th>
                                    <th>Female Rejected</th>
                                    <th>Rejected Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inspection->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->product_name ?? 'N/A' }}</td>
                                    <td>{{ $detail->received_qty }}</td>
                                    <td>{{ $detail->male_accepted_qty }}</td>
                                    <td>{{ $detail->female_accepted_qty }}</td>
                                    <td>{{ $detail->male_rejected_qty }}</td>
                                    <td>{{ $detail->female_rejected_qty }}</td>
                                    <td>{{ $detail->rejectMaster->rejected_reasons ?? 'N/A' }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

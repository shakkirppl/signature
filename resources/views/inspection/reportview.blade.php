@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Inspection Report Details</h4>
                      <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="order_no" class="form-label">Order No:</label>
                            <input type="text" class="form-control" id="order_no" value="{{ $inspection->order_no }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="date" class="form-label">Date:</label>
                            <input type="text" class="form-control" id="date" value="{{ $inspection->date }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="supplier" class="form-label">Supplier:</label>
                            <input type="text" class="form-control" id="supplier" value="{{ $inspection->supplier->name }}" readonly>
                        </div>
                    </div>

                   <br>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Accepted Quantity</th>
                                    <th>Rejected Quantity</th>
                                    <th>Rejected Reason</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inspection->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>{{ $detail->accepted_qty }}</td>
                                    <td>{{ $detail->rejected_qty }}</td>
                                    <td>{{ optional($detail->rejectMaster)->rejected_reasons }}</td>
                                    <td>{{ number_format($detail->rate, 2) }}</td>
                                    <td>{{ number_format($detail->total, 2) }}</td>
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

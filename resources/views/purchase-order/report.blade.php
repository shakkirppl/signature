@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Purchase Order Report</h4>

                    <form method="GET" action="{{ route('purchase-order.report') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Get</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover" id="report-table">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Grand Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseOrders as $order)
                                <tr>
                                    <td>{{ $order->order_no }}</td>
                                    <td>{{ $order->date }}</td>
                                    <td>{{ $order->supplier ? $order->supplier->name : 'N/A' }}</td>
                                    <td>{{ number_format($order->grand_total, 2) }}</td>
                                    <td>
                                        @if($order->status == 1)
                                            <span class="badge badge-success">Completed</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase-order.view', $order->id) }}" class="btn btn-warning">View</a>
                                    </td>
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

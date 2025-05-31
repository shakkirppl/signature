@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <h4>Pending Delete Requests</h4>
        <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
    <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                    <th>No</th>
        <th>Order No</th>
        <th>Supplier</th>
        <th>Date</th>
        <th>Shipment No</th>
        <th>Sales Order No</th>
        <th>Advance</th>
        <th>Animals Count</th>
        <th>Created By</th>
        <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $index => $order)
                <tr>
                      <td>{{ $index + 1 }}</td>
        <td>{{ $order->order_no }}</td>
        <td>{{ $order->supplier->name ?? 'N/A' }}</td>
        <td>{{ $order->date }}</td>
        <td>{{ $order->shipment->shipment_no ?? 'N/A' }}</td>
        <td>{{ $order->salesOrder->order_no ?? 'N/A' }}</td>
        <td>{{ number_format($order->advance_amount, 2) }}</td>
        <td>{{ $order->details->sum('qty') }}</td>
        <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>
                                  <a href="{{ route('purchase-order.view', $order->id) }}" class="btn btn-info btn-sm">View</a>

                        <a href="{{ route('purchase-order.destroy', $order->id) }}"  class="btn btn-danger btn-sm"   onclick="return confirm('Are you sure you want to delete this record?')">
                            Approve Delete
                       </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

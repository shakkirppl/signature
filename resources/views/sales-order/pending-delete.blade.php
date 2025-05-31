@extends('layouts.layout')
@section('content')
@php $user = Auth::user(); @endphp
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Delete Requests</h4>

          <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Order No</th>
                  <th>Customer</th>
                  <th>Date</th>
                  <th>Grand Total</th>
                  <th>Advance</th>
                  <th>Balance</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($salesOrders as $index => $order)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $order->order_no }}</td>
                  <td>{{ $order->customer->customer_name ?? 'N/A' }}</td>
                  <td>{{ $order->date }}</td>
                  <td>{{ number_format($order->grand_total, 2) }}</td>
                  <td>{{ number_format($order->advance_amount, 2) }}</td>
                  <td>{{ number_format($order->balance_amount, 2) }}</td>
                  <td>
                      <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('goodsout-order.view', $order->id) }}" class="btn btn-info btn-sm">View</a>

                     <a href="{{ route('goodsout-order.destroy', $order->id) }}" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Approve and delete this sales order?')">
                      Approve Delete
                    </a>
</div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center">No pending delete requests found.</td>
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
@endsection

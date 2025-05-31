@extends('layouts.layout')
@section('content')
@php
    $user = Auth::user();
@endphp
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Sales Order List</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('goodsout-order.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <!-- Responsive Table -->
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th style="width: 5%;">No</th>
                  <th style="width: 15%;">Order No</th>
                  <th style="width: 20%;">Customer</th>
                  <th style="width: 10%;">Date</th>
                  <th style="width: 10%;">Grand Total</th>
                  <th style="width: 10%;">Advance</th>
                  <th style="width: 10%;">Balance</th>
                  <th style="width: 20%;">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($salesOrders as $index => $order)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $order->order_no }}</td>
                  <td style="white-space: nowrap;">{{ $order->customer ? $order->customer->customer_name : 'N/A' }}</td>
                  <td>{{ $order->date }}</td>
                  <td>{{ number_format($order->grand_total, 2) }}</td>
                  <td>{{ number_format($order->advance_amount, 2) }}</td>
                  <td>{{ number_format($order->balance_amount, 2) }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('goodsout-order.view', $order->id) }}" class="btn btn-info btn-sm">View</a>
                    @if($user->designation_id == 1)
                    <a href="{{ route('goodsout-order.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('goodsout-order.destroy', $order->id) }}" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure you want to delete this record?')">
                       Delete
                    </a>
                    @endif
                    @if($user->designation_id == 3 )
  <form action="{{ route('goodsout-order.request-delete', $order->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to request deletion?')">
      Request Delete
    </button>
  </form>
@endif
</div>

                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div> <!-- End of table-responsive -->

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Custom CSS for table responsiveness -->
<style>
  .table-responsive {
    overflow-x: auto;
  }
  .table th, .table td {
    padding: 5px;
    text-align: center;
  }
  .btn-sm {
    padding: 3px 6px;
    font-size: 10px;
  }
  .newicon i {
    font-size: 30px;}
</style>

@endsection

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
              <h4 class="card-title"> Sales   List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('sales_payment.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>Order No</th>
                                    <th>Date</th>
                                    <th>Shipment</th>
                                    <th>Customer</th>
                                    <th>Sales No</th>
                                    <th>Grand Total</th>
                                    <th>Shrinkage</th>
                                    <th>Packaging</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($SalesPayments as $sale)
                                    <tr>
                                        <td>{{ $sale->order_no }}</td>
                                        <td>{{ $sale->date }}</td>
                                        <td>{{ $sale->shipment ? $sale->shipment->shipment_no : 'N/A' }}</td>

                                        <td>{{ $sale->customer->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $sale->SalesOrder->order_no ?? 'N/A' }}</td>
                                        <td>{{ number_format($sale->grand_total, 2) }}</td>
                                        <td>{{ $sale->shrinkage }}</td>
                                        <td>{{ $sale->packaging }}</td>
                                        <td>
                                            <a href="{{ route('sales_payment.view', $sale->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('invoice.print', $sale->order_no) }}" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="mdi mdi-printer"></i> Print
                                            </a> 
                                            @if($user->designation_id == 1)
                                            <a href="{{ route('sales_payment.edit', $sale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                           
                                            <a href="{{ route('sales_payment.destroy',  $sale->id) }}" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                     Delete
                                            </a>
                                            @endif
                                                 @if($user->designation_id == 3 && $sale->edit_status == 'none')
                                                 <a href="{{ route('sales_payment.edit-request', $sale->id) }}" class="btn btn-warning btn-sm">
                                                 Request Edit
                                                </a>
                                                @endif

        
                                            @if($user->designation_id == 3 && $sale->delete_status == 0)
                                            <form action="{{ route('sales_payment.request_delete', $sale->id) }}" method="POST" style="display:inline-block;">
                                             @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Request to delete this record?')">
                                             Request Delete
                                            </button>
                                           </form>
                                            @endif

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

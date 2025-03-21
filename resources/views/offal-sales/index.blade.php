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
              <h4 class="card-title">Offal Sales List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('offal-sales.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>Order No</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Shipment No</th>
                                    <th>Grand Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offalSales as $sale)
                                    <tr>
                                        <td>{{ $sale->order_no }}</td>
                                        <td>{{ $sale->date }}</td>
                                        <td>{{ $sale->localcustomer->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $sale->shipment->shipment_no ?? 'N/A' }}</td>
                                        <td>{{ number_format($sale->grand_total, 2) }}</td>
                                        <td>
                                            <a href="{{ route('offal-sales.view', $sale->id) }}" class="btn btn-info btn-sm">View</a>
                                            @if($user->designation_id == 1)
                                            <a href="{{ route('offal-sales.edit', $sale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="{{ route('offal-sales.destroy',  $sale->id) }}" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                     Delete
                                            </a>
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

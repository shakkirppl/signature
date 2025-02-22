@extends('layouts.layout')
@section('content')
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
          <div class="table-responsive">
            <table class="table">
              <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Sales No</th>
                                    <th>Grand Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($SalesPayments as $sale)
                                    <tr>
                                        <td>{{ $sale->order_no }}</td>
                                        <td>{{ $sale->date }}</td>
                                        <td>{{ $sale->customer->customer_name ?? 'N/A' }}</td>
                                        <td>{{ $sale->SalesOrder->order_no ?? 'N/A' }}</td>
                                        <td>{{ number_format($sale->grand_total, 2) }}</td>
                                        <td>
                                            <a href="{{ route('sales_payment.view', $sale->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('sales_payment.edit', $sale->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="{{ route('invoice.print', $sale->order_no) }}" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="mdi mdi-printer"></i> Print
                                            </a> 
                                            <a href="{{ route('sales_payment.destroy',  $sale->id) }}" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                     Delete
                                            </a>
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

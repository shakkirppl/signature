@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">purchase Order List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('purchase-order.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                 
                  <th>Order No</th>
                  <th>Supplier</th>
                  <th>Date</th>
                  <th>Shipment No</th>
                 <th>SalesOrder No</th>
                  <th>Advance Amount</th>
                  <!-- <th>Balance</th> -->
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($purchaseOrders as $index => $order)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $order->order_no }}</td>
                  <td>{{ $order->supplier ? $order->supplier->name : 'N/A' }}</td>
                  <td>{{ $order->date }}</td>
                  <td>{{ $order->shipment ? $order->shipment->shipment_no : 'N/A' }}</td>

                  <td>{{ $order->salesOrder ? $order->salesOrder->order_no : 'N/A' }}</td>

                
                  
                  <!-- <td>{{ $order->grand_total }}</td> -->
                  <td>{{ $order->advance_amount }}</td>
                  <!-- <td>{{ $order->balance_amount }}</td> -->
                       
                  <td>
                  <a href="{{ route('purchase-order.view', $order->id) }}" class="btn btn-info btn-sm">View</a>

                  <a href="{{ route('purchase-order.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                       
                  <a href="{{ route('purchase-order.destroy',  $order->id) }}" 
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
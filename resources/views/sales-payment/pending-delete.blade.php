@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Delete Requests</h4>
          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead>
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
                @forelse ($SalesPayments as $sale)
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

                     <a href="{{ route('sales_payment.destroy',  $sale->id) }}" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Approve and delete permanently?')">
                                                      Approve Delete
                                            </a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="9" class="text-center">No pending requests found.</td>
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

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
          <div class="row mb-3">
            <div class="col-md-6">
              <h4 class="card-title">Purchase Order List</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('purchase-order.create') }}" class="newicon">
                <i class="mdi mdi-new-box"></i>
              </a>
            </div>
          </div>

          <!-- Search Dropdown -->
          <div class="row mb-3">
            <div class="col-md-4">
              <form action="{{ route('purchase-order.index') }}" method="GET">
                <div class="input-group">
                  <select name="supplier_id" class="form-control select2" id="supplierSelect" onchange="this.form.submit()">
                    <option value="">-- Select Supplier --</option>
                    @foreach($suppliers as $supplier)
                      <option value="{{ $supplier->id }}" {{ isset($supplierId) && $supplierId == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                      </option>
                    @endforeach
                  </select>
                  <div class="input-group-append ml-2">
                    <a href="{{ route('purchase-order.index') }}" class="btn btn-primary btn">Reset</a>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-md-6 text-right">
              <h5><strong>Total Advance Amount:</strong> {{ number_format($totalAdvance, 2) }}</h5>
              @if(isset($supplierId))
                <h5><strong>Total Animals:</strong>{{ $totalQuantity }}</h5>
              @endif
            </div>
          </div>

          <!-- Responsive Table -->
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
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
                @foreach ($purchaseOrders as $index => $order)
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
                    @if($user->designation_id == 1)
                      <a href="{{ route('purchase-order.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
                      <a href="{{ route('purchase-order.destroy', $order->id) }}"
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
          </div> <!-- End of table-responsive -->

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Select2 CDN (for searchable dropdown) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: "Select a supplier",
      allowClear: true
    });
  });
</script>

<!-- Custom CSS -->
<style>
  .table th, .table td {
    padding: 5px;
    text-align: center;
  }
  .btn-sm {
    padding: 3px 6px;
    font-size: 10px;
  }
  .newicon i {
    font-size: 30px;
  }
</style>

@endsection

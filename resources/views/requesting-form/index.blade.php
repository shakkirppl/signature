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
              <h4 class="card-title">Requesting Form List</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('requesting-form.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                <th>No</th>
                  <th>Invoice No</th>
                  <th>Order No</th>
                  <th>Supplier No</th>
                  <th>Date</th>
                  <th>Supplier</th>
                  <th>Shipment</th>
                  <th>Sales Order</th>
                 
                 
                  <th>Advance Amount</th>
                 
                  
                  <th>Created By</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($requestingForms as $index => $form)
                <tr>
                  <td>{{ $index + 1 }}</td>
                
                  <td>{{ $form->invoice_no }}</td>
                  <td>{{ $form->order_no }}</td>
                  <td>{{ $form->supplier_no }}</td>
                  <td>{{ $form->date }}</td>
                  <td>{{ $form->supplier->name ?? 'N/A' }}</td>
                  <td>{{ $form->shipment->shipment_no ?? 'N/A' }}</td>
                  <td>{{ $form->salesOrder->order_no ?? 'N/A' }}</td>
                  <td>{{ number_format($form->advance_amount, 2) }}</td>
                  <td>{{ $form->user->name ?? 'N/A' }}</td>
                 <td>
  @if($user->designation_id == 1)
    <a href="{{ route('requesting-form.show', $form->id) }}" class="btn btn-info btn-sm">View</a>
    
    <form action="{{ route('requesting-form.accept', $form->id) }}" method="POST" style="display:inline-block;">
      @csrf
      <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Accept this request?')">Accept</button>
    </form>

    <form action="{{ route('requesting-form.reject', $form->id) }}" method="POST" style="display:inline-block;">
      @csrf
      <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this request?')">Reject</button>
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

<!-- Custom CSS for responsiveness -->
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
    font-size: 30px;
  }
</style>
@endsection

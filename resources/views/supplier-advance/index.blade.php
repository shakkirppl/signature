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
              <h4 class="card-title">Supplier Advance List</h4>
            </div>
             @if($user->designation_id == 1||$user->designation_id == 3)
            <div class="col-md-6 text-right">
            <a href="{{ route('supplieradvance.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
            @endif
            <div class="col-md-6 text-right">
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                      <tr>
                  
                                     <th>No</th>
                                    <th>Code</th>
                                    <th>Date</th>
                                    <th>Shipment</th>
                                    <th>PurchaseOrder No</th>
                                    <th>Supplier</th>
                                    <th>Type</th>
                                    <th>Advance Amount</th>
                                    <th>Action</th>
                                   
                                  </tr>
                                     </thead>
                                     <tbody>
                                     @if($supplierAdvances->isEmpty())
                                        <tr><td colspan="9">No data available</td></tr>
                                     @else
                                      @foreach($supplierAdvances as $key => $advance)
                                 <tr>
                                     <td>{{ $key + 1 }}</td>
                                     <td>{{ $advance->code }}</td>
                                     <td>{{ $advance->date }}</td>
                                     <td>{{ $advance->shipment->shipment_no ?? 'N/A' }}</td>
                                     <td>{{$advance->order_no}}</td>
                                     <td>{{ $advance->supplier->name ?? 'N/A' }}</td>
                                     <td>{{ ucfirst($advance->type) }}</td>
                                     <td>{{ number_format($advance->advance_amount, 2) }}</td>
                                     @if($user->designation_id == 1)
<td>
    <form action="{{ route('supplieradvance.destroy', $advance->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>
@endif
    @if($user->designation_id == 3 && $advance->delete_status == 0)
<td>
    <form action="{{ route('supplieradvance.requestDelete', $advance->id) }}" method="POST" onsubmit="return confirm('Request to delete this Supplier Advance?');">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger">Request Delete</button>
    </form>
</td>
@endif

</td>



                                 </tr>
                                   @endforeach
                                    @endif

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

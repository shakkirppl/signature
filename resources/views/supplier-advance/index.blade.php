@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Supplier Advance List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('supplieradvance.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
            <div class="col-md-6 text-right">
            </div>
          </div>
                   <div class="table-responsive">
                             <table class="table">
                                 <thead>
                                      <tr>
                  
                                     <th>No</th>
                                    <th>Code</th>
                                    <th>Date</th>
                                    <th>Shipment</th>
                                    <th>PurchaseOrder No</th>
                                    <th>Supplier</th>
                                    <th>Type</th>
                                    <th>Advance Amount</th>
                                   
                                  </tr>
                                     </thead>
                                     <tbody>
                                     @if($supplierAdvances->isEmpty())
                                        <tr><td colspan="8">No data available</td></tr>
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
@endsection

@extends('layouts.layout') 

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
            <div class="col-md-6 text-right">
                        <a href="{{ url()->previous() }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
          </div>
              <h4 class="card-title">Rejected Products </h4>
            </div>
            <div class="col-md-2">
              <h5 >  Shipment No: {{ $shipment_no }}</h5>
            </div>
           
            <div class="col-md-6 text-right">
              <!-- <a href="{{ route('rejected.animal.report') }}" class="btn btn-secondary"></a> -->
            </div>
          </div>
          <div class="table-responsive">
          <table class="table">
  <thead>
    <tr>
      <th>No</th>
      <th>Supplier</th>
      <th>Total Male Rejected Quantity</th>
      <th>Total Female Rejected Quantity</th>
      <th>Total Rejected Quantity</th>
      <th>Action</th>
      
    </tr>
  </thead>
  <tbody>
    @foreach ($shipmentDetails as $index => $detail)
    <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $detail->supplier_name }}</td>
      <td>{{ $detail->total_male_rejected_qty }}</td>
      <td>{{ $detail->total_female_rejected_qty }}</td>
      <td>{{ $detail->total_male_rejected_qty + $detail->total_female_rejected_qty }}</td>
      <td>
        <a href="{{ route('supplier.rejected.details', ['shipment_no' => $shipment_no, 'supplier_name' => $detail->supplier_name]) }}" class="btn btn-info btn-sm">View</a>
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

@extends('layouts.layout') 

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
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
                  <th>Product Name</th>
                  <th>Rejected Quantity</th>
                  <th>Rejection Reason</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($shipmentDetails as $index => $detail)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $detail->name }}</td>
                  <td>{{ $detail->product_name }}</td>
                  <td>{{ $detail->rejected_qty }}</td>
                  <td>{{ $detail->rejected_reason ?? 'N/A' }}</td>
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

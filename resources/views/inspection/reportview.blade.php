@extends('layouts.layout')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        
        <div class="card-body">
        
          <div class="col-md-6 text-right">
                     <a href="{{ url()->previous() }}" class="backicon">
                    <i class="mdi mdi-backburger"></i>
                   </a>
          </div>
          <h4 class="card-title">Rejected Products - {{ $supplier_name }}</h4>
          <h5>Shipment No: {{ $shipment_no }}</h5>
          
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Product Name</th>
                  <th>Male Rejected Quantity</th>
                  <th>Female Rejected Quantity</th>
                  <th>Rejection Reason</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rejectedProducts as $index => $product)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $product->product_name }}</td>
                  <td>{{ $product->male_rejected_qty }}</td>
                  <td>{{ $product->female_rejected_qty }}</td>
                  <td>{{ $product->rejected_reason ?? 'N/A' }}</td>
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


@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Shipments Report</h4>
            </div>
          
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                
                <th>Shipment NO</th>
                <th>Date</th>
                 <th>Time</th>
                 <th>Action</th>
                </tr>
              </thead>
              <tbody>
              @foreach($shipments as $shipment)
                                <tr>
                                  
                                    <td>{{ $shipment->shipment_no }}</td>
                                    <td>{{ $shipment->date }}</td>
                                    <td>{{ $shipment->time }}</td>
                                    <td>
                                    <a href="{{ route('shipment-suppllier-final-payment-report', $shipment->id) }}" class="btn btn-warning btn-sm">Supplier Final Payment Report</a>
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










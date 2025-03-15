@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Weight Calculator</h4>
            </div>
            <div class="col-md-6 text-right">
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Shipment No</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($shipments as $shipment)
                <tr>
                    <td>{{ $shipment->shipment_no }}</td>
                    <td>
                        <a href="{{ route('weight_calculator.create', $shipment->shipment_id) }}" class="btn btn-primary">
                            @if($shipment->is_completed)
                                View Details
                            @else
                                Weight Calculator
                            @endif
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

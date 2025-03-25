
@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Shipments</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('shipment.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                <th>ID</th>
                <th>Shipment NO</th>
                <th>Date</th>
                 <th>Time</th>
                 <th>Action</th>
                </tr>
              </thead>
              <tbody>
              @foreach($shipments as $shipment)
                                <tr>
                                    <td>{{ $shipment->id }}</td>
                                    <td>{{ $shipment->shipment_no }}</td>
                                    <td>{{ $shipment->date }}</td>
                                    <td>{{ $shipment->time }}</td>
                                    <td>
    <a href="{{ route('shipmentprofit.report', $shipment->id) }}" class="btn btn-info btn-sm" style="display: inline-block;">
        <i class="mdi mdi-file-document"></i> Report
    </a>
    
    <form action="{{ route('shipment.destroy', $shipment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this shipment?');" style="display: inline-block; margin-left: 5px;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="mdi mdi-delete"></i> Remove
        </button>
    </form>
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










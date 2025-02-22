
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
          <div class="table-responsive">
            <table class="table">
              <thead>
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
                                    <form action="{{ route('shipment.destroy', $shipment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this shipment?');">
                                    @csrf
                                    @method('DELETE')
                                   <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i> Remove</button>
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
@endsection










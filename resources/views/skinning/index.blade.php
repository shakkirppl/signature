
@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Skinning List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('skinning.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                        <th>No</th>
                        <th>Skinning Code</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Shipment No</th>
                        <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($skinningRecords as $index => $record)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $record->skinning_code }}</td>
                <td>{{ $record->date }}</td>
                <td>{{ $record->time }}</td>
                <td>{{ $record->shipment->shipment_no ?? 'N/A' }}</td>
               
                       
                  <td>
                  <a href="{{ route('skinning.view', $record->id) }}" class="btn btn-info btn-sm">View</a>

                  <a href="{{ route('skinning.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                       
                  <a href="{{ route('skinning.destroy', $record->id) }}" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure you want to delete this record?')">
                       Delete
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

@extends('layouts.layout')
@section('content')

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Animal Receive Notes</h4>
          <div class="col-md-12 text-right">
            </div>
          <!-- Success Message -->
          @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                <th>Inspection No</th>
                <th>Order No</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>Shipment</th>
                <th>created By</th>
                <th>Actions</th>
            </tr>
            <tbody>
              @foreach($inspections as $inspection)
            <tr>
                <td>{{ $inspection->inspection_no }}</td>
                <td>{{ $inspection->order_no }}</td>
                <td>{{ $inspection->date }}</td>
                <td>{{ $inspection->supplier->name ?? '' }}</td>
                <td>{{ $inspection->shipment->shipment_no ?? '' }}</td>
                <td>{{ $inspection->user->name ?? '' }}</td>

                <td>
                    <a href="{{ route('animalReceive.view', $inspection->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('animalReceive.print', $inspection->id) }}" class="btn btn-secondary btn-sm" target="_blank">Print</a>
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
    font-size: 30px;
  }
</style>

@endsection

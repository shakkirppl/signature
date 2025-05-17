@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Accountant Manager Approvals</h4>
            </div>
            <div class="col-md-6 text-right">
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
           
      <thead>
        <tr>
            <th>Shipment No</th>
            <th>Supplier</th>
             <th>Total quandity</th>
            <th>Total Weight</th>
            <th>Document</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $entry)
        <tr>
            <td>{{ $entry->shipment->shipment_no ?? '' }}</td>
            <td>{{ $entry->supplier->name ?? '' }}</td>
             <td>{{ $entry->details->sum('total_accepted_qty') }}</td>
            <td>{{ $entry->total_weight }}</td>
            <td>
                @if ($entry->document)
                    <a href="{{ asset('storage/' . $entry->document) }}" target="_blank">View Document</a>
                @else
                    No Document
                @endif
            </td>
            <td>
                <form action="{{ route('weight_approval.accountant.approve', $entry->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success">Approve</button>
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

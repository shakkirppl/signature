@extends('layouts.layout')
@section('content')
@php
    $user = Auth::user();
@endphp
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                <div class="col-6 col-md-6 col-sm-6 col-xs-12  heading" style="text-align:end;">
                    <a href="{{ route('offal-receive.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                    </div>

                    <h4 class="card-title">Offal Receives list</h4>
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>No</th>
                                    <th>Order No</th>
                                    <th>Date</th>
                                    <th>Shipment No</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Good Offal</th>
                                    <th>Damaged Offal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($offalReceives as $key => $offalReceive)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $offalReceive->order_no }}</td>
                                        <td>{{ $offalReceive->date }}</td>
                                        <td>{{ $offalReceive->shipments->shipment_no ?? 'N/A' }}</td>
                                        <td>{{ $offalReceive->products->product_name ?? 'N/A' }}</td>
                                        <td>{{ $offalReceive->qty }}</td>
                                        <td>{{ $offalReceive->good_offal }}</td>
                                        <td>{{ $offalReceive->damaged_offal }}</td>
                                        <td>
                                            <a href="{{ route('offal-receive.edit', $offalReceive->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('offal-receive.destroy', $offalReceive->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
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

@extends('layouts.layout')
@section('content')

@php
  $user = Auth::user();
@endphp

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Dispatch Record List</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('dispatch-record.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <!-- Responsive Table -->
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th style="width: 5%;">No</th>
                  <th style="width: 15%;">Date</th>
                  <th style="width: 15%;">No. of Carcasses</th>
                  <th style="width: 20%;">Customer Name</th>
                  <th style="width: 15%;">Temperature</th>
                  <th style="width: 15%;">Packaging</th>
                  <th style="width: 25%;">Comments</th>
                  <th>Production Date</th>
                  <th>Expire Date</th>
                  <th style="width: 20%;">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($dispatches as $index => $dispatch)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($dispatch->date)->format('d-m-Y') }}</td>
                    <td>{{ $dispatch->no_of_carcasses }}</td>
                    <td>{{ $dispatch->customer_name }}</td>
                    <td>{{ $dispatch->dispatch_temperature }}</td>
                    <td>{{ $dispatch->packaging_material_used }}</td>
                    <td>{{ $dispatch->comments }}</td>
                    <td>{{ $dispatch->production_date }}</td>
                    <td>{{ $dispatch->expire_date }}</td>
                    <td>
                      @if($user->designation_id == 1)
                        <a href="{{ route('dispatch-record.edit', $dispatch->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('dispatch-record.destroy', $dispatch->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                      @else
                        <span class="text-muted">No Access</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> <!-- End of table-responsive -->

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Custom CSS -->
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

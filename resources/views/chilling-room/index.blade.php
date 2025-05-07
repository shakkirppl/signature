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
              <h4 class="card-title">Chilling Room Records</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('chilling-room.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <!-- Responsive Table -->
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th style="width: 5%;">No</th>
                  <th style="width: 15%;">Date</th>
                  <th style="width: 15%;">Time</th>
                  <th style="width: 15%;">Initial Carcass Temp</th>
                  <th style="width: 15%;">Exit Temp</th>
                  <th style="width: 15%;">Chiller Temp & Humidity</th>
                  <th style="width: 20%;">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($records as $index => $record)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                    <td>{{ $record->time }}</td>
                    <td>{{ $record->initial_carcass_temp }}</td>
                    <td>{{ $record->exit_temp_carcass }}</td>
                    <td>{{ $record->chiller_temp_humidity }}</td>
                    <td>
                      @if($user->designation_id == 1)
                        <a href="{{ route('chilling-room.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('chilling-room.destroy', $record->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this record?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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

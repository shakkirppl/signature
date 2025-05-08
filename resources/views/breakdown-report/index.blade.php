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
              <h4 class="card-title">Breakdown Report List</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('breakdown-report.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <!-- Responsive Table -->
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Equipment ID</th>
                  <th>Problem Reported</th>
                  <th>Action Taken</th>
                  <th>Time Out of Service</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($reports as $index => $report)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</td>
                    <td>{{ $report->equipment_id }}</td>
                    <td>{{ $report->problem_reported }}</td>
                    <td>{{ $report->action_taken }}</td>
                    <td>{{ $report->time_out_of_service }}</td>
                    <td>
                      @if($user->designation_id == 1)
                        <a href="{{ route('breakdown-report.edit', $report->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('breakdown-report.destroy', $report->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
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

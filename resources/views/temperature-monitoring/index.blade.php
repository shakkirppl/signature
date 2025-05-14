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

        @if(session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
        @endif

          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Temperature Monitoring Records</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('temperature-monitoring.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Temp Before</th>
                  <th>Temp After</th>
                  <th>Slaughter Area</th>
                  <th>Average Carcass</th>
                  <th>Inspector Name</th>
                  <th>Signature</th>
                   
                  <th>Created By</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($records as $index => $record)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $record->date }}</td>
                  <td>{{ $record->time }}</td>
                  <td>{{ $record->temp_before }}</td>
                  <td>{{ $record->temp_after }}</td>
                  <td>{{ $record->slaughter_area }}</td>
                  <td>{{ $record->average_carcass }}</td>
                  <td>{{ $record->inspector_name }}</td>
                 
     <td>
    @if($record->inspector_signature)
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#signatureModal{{ $record->id }}">
            View
        </button>

        <!-- Modal for viewing signature -->
        <div class="modal fade" id="signatureModal{{ $record->id }}" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel{{ $record->id }}" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel{{ $record->id }}">Inspector Signature</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-center">
                <img src="{{ asset('uploads/signatures/' . $record->inspector_signature) }}" alt="Signature" style="width: 100%; max-width: 800px; height: auto;">
              </div>
            </div>
          </div>
        </div>
    @else
        N/A
    @endif
</td>
                  <td>{{ $record->user->name ?? 'N/A' }}</td>



                  <td>
                    {{-- You can add edit/delete buttons if needed --}}
                    {{-- Example:
                    <a href="{{ route('temperature-monitoring.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    --}}
                  </td>
                </tr>
                @endforeach
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap CSS -->


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

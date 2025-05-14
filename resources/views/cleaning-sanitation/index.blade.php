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
              <h4 class="card-title">Cleaning & Sanitation Records</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('cleaning-sanitation.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Cleaning Method</th>
                  <th>Cleaning Agent</th>
                  <th>Area Cleaned</th>
                  <th>Cleaner Name</th>
                  <th>Supervisor Check</th>
                  <th>Signature</th>
                  <th>Created By</th>
                   <th>Comments</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($records as $index => $record)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $record->date }}</td>
                  <td>{{ $record->cleaning_method }}</td>
                  <td>{{ $record->cleaning_agent }}</td>
                  <td>{{ $record->area_cleaned }}</td>
                  <td>{{ $record->cleaner_name }}</td>
                  <td>{{ $record->supervisor_check }}</td>
                  <td>
                    @if($record->verification_signature)
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#signatureModal{{ $record->id }}">
                        View
                      </button>

                      <!-- Modal -->
                      <div class="modal fade" id="signatureModal{{ $record->id }}" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel{{ $record->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="signatureModalLabel{{ $record->id }}">Signature</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body text-center">
                              <img src="{{ asset('uploads/signatures/' . $record->verification_signature) }}" alt="Signature" style="width: 100%; max-width: 800px; height: auto;">
                            </div>
                          </div>
                        </div>
                      </div>
                    @else
                      N/A
                    @endif
                  </td>
                  <td>{{ $record->user->name ?? 'N/A' }}</td>
                  <td>{{ $record->comments }}</td>
                  <td>
                    {{-- <a href="{{ route('cleaning-sanitation.edit', $record->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
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

<!-- Bootstrap JS (only if not already included globally) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

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

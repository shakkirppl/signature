@extends('layouts.layout')
@section('content')

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Calibration Records</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('calibration-record.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Equipment Name</th>
                  <th>Standard Used</th>
                  <th>Result</th>
                  <th>Next Due</th>
                  <th>Technician</th>
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
                  <td>{{ $record->equipment_name }}</td>
                  <td>{{ $record->standard_used }}</td>
                  <td>{{ Str::limit($record->calibration_result, 30) }}</td>
                  <td>{{ $record->next_calibration_due }}</td>
                  <td>{{ $record->technician_name }}</td>
                  <td>
                    @if($record->signature)
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#signatureModal{{ $record->id }}">
                        View
                      </button>

                      <!-- Signature Modal -->
                      <div class="modal fade" id="signatureModal{{ $record->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Technician Signature</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body text-center">
                              <img src="{{ asset('storage/signatures/' . $record->signature) }}" alt="Signature" style="width: 100%; max-width: 800px; height: auto;">
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
                     <div class="d-flex align-items-center gap-1">
                     <a href="{{ route('calibration-record.edit', $record->id) }}" class="btn btn-sm btn-warning">
      Edit
    </a> <form action="{{ route('calibration-record.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
  </form>
 </div>
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

<!-- Bootstrap JS for modal -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
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

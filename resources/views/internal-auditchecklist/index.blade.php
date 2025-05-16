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
              <h4 class="card-title">Internal Audit Checklist and Report</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('internal-auditchecklist.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Audit Date</th>
                  <th>Area Audited</th>
                  <th>Auditor Name</th>
                  <th>Non-Conformities</th>
                  <th>Corrective Actions</th>
                  <th>Follow-Up Date</th>
                  <th>Auditor Signature</th>
                  <th>Created By</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($records as $index => $record)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $record->audit_date }}</td>
                  <td>{{ $record->area_audited }}</td>
                  <td>{{ $record->auditor_name }}</td>
                  <td>{{ Str::limit($record->non_conformities_found, 30) }}</td>
                  <td>{{ Str::limit($record->corrective_actions_needed, 30) }}</td>
                  <td>{{ $record->follow_up_date }}</td>
                  <td>
                    @if($record->auditor_signature)
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#signatureModal{{ $record->id }}">
                        View
                      </button>

                      <!-- Modal -->
                      <div class="modal fade" id="signatureModal{{ $record->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Auditor Signature</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body text-center">
                            <img src="{{ asset('storage/signatures/' . $record->auditor_signature) }}" alt="Signature" style="width: 100%; max-width: 800px; height: auto;">

                            </div>
                          </div>
                        </div>
                      </div>
                    @else
                      N/A
                    @endif
                  </td>
                  <td>{{ $record->user->name ?? 'N/A' }}</td>
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

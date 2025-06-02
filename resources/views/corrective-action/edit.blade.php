@extends('layouts.layout')
@section('content')

<style>
  .required:after {
    content: " *";
    color: red;
  }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Corrective Action Report</h4>
          <div class="col-md-6 heading">
            <a href="{{ route('corrective-action.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
          </div>

          @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form id="temperatureForm" action="{{ route('corrective-action.update', $record->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">

                <div class="form-group">
                  <label for="date" class="required">Date</label>
                  <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $record->date) }}" required>
                </div>

                <div class="form-group">
                  <label for="non_conformity" class="required">Description of non-conformity</label>
                  <input type="text" class="form-control" id="non_conformity" name="non_conformity" value="{{ old('non_conformity', $record->non_conformity) }}" required>
                </div>

                <div class="form-group">
                  <label for="action_taken" class="required">Corrective Action Taken</label>
                  <input type="text" class="form-control" id="action_taken" name="action_taken" value="{{ old('action_taken', $record->action_taken) }}" required>
                </div>

                <div class="form-group">
                  <label for="responsible_person" class="required">Responsible Person</label>
                  <input type="text" class="form-control" id="responsible_person" name="responsible_person" value="{{ old('responsible_person', $record->responsible_person) }}" required>
                </div>
                @if ($record->signature)
                    <div class="mt-2">
                      <small>Current Signature:</small><br>
                      <img src="{{ asset('storage/signatures/' . $record->signature) }}" width="200" alt="Signature">
                    </div>
                  @endif

              </div>

              

              <div class="col-md-6">

                <div class="form-group">
                  <label for="department">Department</label>
                  <input type="text" class="form-control" id="department" name="department" value="{{ old('department', $record->department) }}">
                </div>

                <div class="form-group">
                  <label for="root_cause" class="required">Root Cause</label>
                  <input type="text" class="form-control" id="root_cause" name="root_cause" value="{{ old('root_cause', $record->root_cause) }}" required>
                </div>

                <div class="form-group">
                  <label for="date_of_completion" class="required">Date Of Completion</label>
                  <input type="date" class="form-control" id="date_of_completion" name="date_of_completion" value="{{ old('date_of_completion', $record->date_of_completion) }}" required>
                </div>

                <div class="form-group">
                  <label for="verified_by" class="required">Verified By</label>
                  <input type="text" class="form-control" id="verified_by" name="verified_by" value="{{ old('verified_by', $record->verified_by) }}" required>
                </div>

                <div class="form-group">
                  <label class="required">Inspector Signature</label><br>
                  <canvas id="signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas><br>
                  <button type="button" class="btn btn-sm btn-warning mt-2" onclick="clearSignature()">Clear</button>
                  <input type="hidden" name="signature" id="signature">

                  
                </div>

              </div>
            </div>

            <div class="submitbutton">
              <button type="submit" class="btn btn-primary mb-2 submit">Update <i class="fas fa-save"></i></button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    window.clearSignature = function () {
      signaturePad.clear();
    };

    document.getElementById('temperatureForm').addEventListener('submit', function (e) {
      if (!signaturePad.isEmpty()) {
        document.getElementById('signature').value = signaturePad.toDataURL();
      }
    });
  });
</script>

@endsection
